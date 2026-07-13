<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveMaterialDispatchRequest;
use App\Http\Requests\DispatchMaterialRequest;
use App\Http\Requests\ReceiveMaterialRequest;
use App\Http\Requests\RejectMaterialDispatchRequest;
use App\Http\Requests\ResolveDiscrepancyRequest;
use App\Models\Material;
use App\Models\MaterialDispatch;
use App\Models\MaterialDispatchItem;
use App\Models\MaterialRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MaterialDispatchController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:material-dispatch.index', only: ['index']),
            new Middleware('permission:material-dispatch.edit', only: ['approve', 'reject', 'dispatch', 'receive']),
            new Middleware('role:Kitchen Staff', only: ['resolve']),
            new Middleware('permission:material-dispatch.resolve', only: ['resolve']),
        ];
    }

    public function index()
    {
        // Material requests waiting for approval or rejection
        $pendingRequests = MaterialRequest::with(['user','items.material',])->where('status', 'pending')->latest()->get();

        // Approved and waiting to be dispatched
        $approvedDispatches = MaterialDispatch::with(['request.user', 'items'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Fully dispatched
        $dispatched = MaterialDispatch::with(['request.user', 'items'])
            ->where('status', 'dispatched')
            ->latest()
            ->get();

        // Partially dispatched
        $partialDispatches = MaterialDispatch::with(['request.user', 'items'])
            ->where('status', 'partially_dispatched')
            ->latest()
            ->get();

        // Successfully received / completed
        $received = MaterialDispatch::with(['request.user', 'items'])
            ->where('status', 'completed')
            ->latest()
            ->get();

        // Received with missing quantity
        $discrepancy = MaterialDispatch::with(['request.user', 'items'])
            ->where('status', 'received_with_discrepancy')
            ->latest()
            ->get();

        // Rejected requests
        $rejected = MaterialDispatch::with(['request.user', 'items'])
            ->where('status', 'rejected')
            ->latest()
            ->get();

        return view('stocks.material-dispatch.list', compact(
            'pendingRequests',
            'approvedDispatches',
            'dispatched',
            'partialDispatches',
            'received',
            'discrepancy',
            'rejected'
        ));
    }

    public function approve(ApproveMaterialDispatchRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {

            $materialRequest = MaterialRequest::with('items')
                ->where('id', $validated['material_request_id'])
                ->lockForUpdate()
                ->firstOrFail();


            if ($materialRequest->status !== 'pending') {

                throw ValidationException::withMessages([
                    'material_request_id' =>
                        'Only pending requests can be approved.',
                ]);
            }


            $dispatch = MaterialDispatch::create([
                'dispatch_no' =>
                    'MD-' . str_pad(
                        MaterialDispatch::count() + 1,
                        6,
                        '0',
                        STR_PAD_LEFT
                    ),

                'material_request_id' => $materialRequest->id,

                'dispatched_by' => Auth::id(),

                'dispatched_at' => now(),

                'status' => 'partially_dispatched',
            ]);


            foreach ($validated['items'] as $itemData) {

                $requestItem = $materialRequest->items()
                    ->where('id', $itemData['request_item_id'])
                    ->firstOrFail();


                $dispatchQty = (float) $itemData['dispatch_qty'];

                $requestedQty = (float) $requestItem->requested_qty;


                if ($dispatchQty > $requestedQty) {

                    throw ValidationException::withMessages([
                        'items' =>
                            'Dispatch quantity cannot exceed requested quantity.',
                    ]);
                }


                $material = Material::where(
                    'id',
                    $requestItem->material_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();


                if ($dispatchQty > $material->current_stock) {

                    throw ValidationException::withMessages([
                        'items' =>
                            "Insufficient stock for {$material->material_name}.",
                    ]);
                }


                // Deduct stock
                $material->decrement(
                    'current_stock',
                    $dispatchQty
                );


                // Create dispatch item
                $dispatch->items()->create([

                    'material_request_item_id' => $requestItem->id,

                    'material_id' => $requestItem->material_id,

                    'dispatched_qty' => $dispatchQty,

                    'received_qty' => 0,

                    'missing_qty' => 0,
                ]);
            }


            $dispatch->load('items.requestItem');


            // Check if anything is still remaining
            $hasRemaining = $dispatch->items->contains(
                function ($item) {

                    return (float) $item->dispatched_qty
                        < (float) $item->requestItem->requested_qty;
                }
            );


            $dispatch->update([

                'status' => $hasRemaining
                    ? 'partially_dispatched'
                    : 'dispatched',
            ]);


            $materialRequest->update([
                'status' => 'approved',
            ]);
        });


        return redirect()
            ->route('material-dispatch.index')
            ->with([
                'message' => 'Material approved and dispatched successfully.',
                'alert-type' => 'success',
            ]);
    }


    /**
     * Reject Material Request
     */
    public function reject(RejectMaterialDispatchRequest $request)
    {
        $validated = $request->validated();

        $materialRequest = MaterialRequest::with('items')
            ->findOrFail($validated['material_request_id']);

        DB::transaction(function () use ($materialRequest, $validated) {

            // Update request status
            $materialRequest->update([
                'status' => 'rejected',
            ]);

            // Create rejected dispatch record
            $dispatch = MaterialDispatch::create([
                'dispatch_no' => 'MD-' . str_pad(MaterialDispatch::count() + 1, 6, '0', STR_PAD_LEFT),
                'material_request_id' => $materialRequest->id,
                'remarks' => $validated['reject_reason'],
                'dispatched_by' => auth()->id(),
                'status' => 'rejected',
            ]);

            // Create dispatch items
            foreach ($materialRequest->items as $requestItem) {

                $dispatch->items()->create([
                    'material_request_item_id' => $requestItem->id,
                    'material_id' => $requestItem->material_id,
                    'dispatched_qty' => 0,
                    'received_qty' => 0,
                    'missing_qty' => 0,
                ]);
            }
        });

        return redirect()
            ->route('material-dispatch.index')
            ->with('success', 'Material request rejected successfully.');
    }


    /**
     * Dispatch Materials
     * Handles both:
     * 1. Partial Dispatch
     * 2. Full Dispatch
     */
    public function dispatch(DispatchMaterialRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {

            // Get dispatch and lock it
            $dispatch = MaterialDispatch::where(
                'id',
                $validated['material_dispatch_id']
            )
                ->lockForUpdate()
                ->firstOrFail();


            // Only pending or partial dispatch can be dispatched
            if (
                !in_array($dispatch->status, [
                    'pending',
                    'partially_dispatched',
                ])
            ) {

                throw ValidationException::withMessages([
                    'material_dispatch_id' =>
                        'This material request cannot be dispatched.',
                ]);
            }


            foreach ($validated['items'] as $itemData) {

                // Get dispatch item
                $dispatchItem = MaterialDispatchItem::with('requestItem')
                    ->where('material_dispatch_id', $dispatch->id)
                    ->findOrFail($itemData['id']);


                $dispatchQty = (float) $itemData['dispatch_qty'];

                $requestedQty =
                    (float) $dispatchItem->requestItem->requested_qty;

                $alreadyDispatched =
                    (float) $dispatchItem->dispatched_qty;

                $remainingQty =
                    $requestedQty - $alreadyDispatched;


                // Prevent over-dispatch
                if ($dispatchQty > $remainingQty) {

                    throw ValidationException::withMessages([
                        'items' =>
                            'Dispatch quantity cannot be greater than remaining quantity.',
                    ]);
                }


                // Get material and lock stock
                $material = Material::where(
                    'id',
                    $dispatchItem->material_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();


                // Check stock
                if ($dispatchQty > $material->current_stock) {

                    throw ValidationException::withMessages([
                        'items' =>
                            "Insufficient stock for {$material->material_name}.",
                    ]);
                }


                // Deduct stock
                $material->decrement(
                    'current_stock',
                    $dispatchQty
                );


                // Add quantity to previous dispatched quantity
                $dispatchItem->increment(
                    'dispatched_qty',
                    $dispatchQty
                );
            }


            // Reload updated quantities
            $dispatch->load('items.requestItem');


            // Check whether any quantity is still remaining
            $hasRemaining = $dispatch->items->contains(
                function ($item) {

                    return (float) $item->dispatched_qty
                        < (float) $item->requestItem->requested_qty;
                }
            );


            // Automatically decide status
            $dispatch->update([

                'status' => $hasRemaining
                    ? 'partially_dispatched'
                    : 'dispatched',

                'dispatched_by' => Auth::id(),

                'dispatched_at' => now(),
            ]);
        });


        return redirect()
            ->route('material-dispatch.index')
            ->with([
                'message' => 'Material dispatched successfully.',
                'alert-type' => 'success',
            ]);
    }

    /**
     * Receive dispatched materials
     */
    public function receive(ReceiveMaterialRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {

            // Get dispatch
            $dispatch = MaterialDispatch::where(
                'id',
                $validated['material_dispatch_id']
            )
                ->lockForUpdate()
                ->firstOrFail();


            // Only fully dispatched materials can be received
            if ($dispatch->status !== 'dispatched') {

                throw ValidationException::withMessages([
                    'material_dispatch_id' =>
                        'Only dispatched materials can be received.',
                ]);
            }


            foreach ($validated['items'] as $itemData) {

                // Get item belonging to this dispatch
                $dispatchItem = MaterialDispatchItem::where(
                    'material_dispatch_id',
                    $dispatch->id
                )
                    ->where('id', $itemData['id'])
                    ->lockForUpdate()
                    ->firstOrFail();


                $receivedQty = (float) $itemData['received_qty'];

                $dispatchedQty = (float) $dispatchItem->dispatched_qty;


                // Cannot receive more than dispatched
                if ($receivedQty > $dispatchedQty) {

                    throw ValidationException::withMessages([
                        'items' =>
                            'Received quantity cannot be greater than dispatched quantity.',
                    ]);
                }


                // Calculate missing quantity
                $missingQty = $dispatchedQty - $receivedQty;


                // Update dispatch item
                $dispatchItem->update([
                    'received_qty' => $receivedQty,
                    'missing_qty' => $missingQty,
                ]);
            }


            // Reload updated items
            $dispatch->load('items');


            // Check if any item has missing quantity
            $hasDiscrepancy = $dispatch->items->contains(function ($item) {

                return (float) $item->missing_qty > 0;

            });


            // Update main dispatch
            $dispatch->update([
                'status' => $hasDiscrepancy
                    ? 'received_with_discrepancy'
                    : 'completed',

                'received_by' => Auth::id(),

                'received_at' => now(),

                'remarks' => $validated['remarks'] ?? $dispatch->remarks,
            ]);
        });


        return redirect()
            ->route('material-dispatch.index')
            ->with([
                'message' => 'Materials received successfully.',
                'alert-type' => 'success',
            ]);
    }

    /**
     * Resolve Material Discrepancy
     */
    /**
     * Resolve Material Discrepancy
     */

    public function resolve(ResolveDiscrepancyRequest $request)
    {
        $validated = $request->validated();

        $dispatch = MaterialDispatch::where(
            'status',
            'received_with_discrepancy'
        )->findOrFail($validated['material_dispatch_id']);

        DB::transaction(function () use ($dispatch) {

            // Keep missing_qty unchanged for history

            $dispatch->update([
                'status' => 'completed',
                'resolved_by' => Auth::id(),
                'resolved_at' => now(),
            ]);
        });

        return redirect()
            ->route('material-dispatch.index')
            ->with([
                'message' => 'Discrepancy resolved successfully.',
                'alert-type' => 'success',
            ]);
    }
}
