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
use App\Models\StockLedger;
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
            new Middleware('permission:material-dispatch.approve', only: ['approve', 'reject']),
            new Middleware('permission:material-dispatch.dispatch', only: ['dispatch']),
            new Middleware('permission:material-dispatch.receive', only: ['receive']),
            new Middleware('role:Kitchen Staff', only: ['receive']),
            new Middleware('permission:material-dispatch.resolve', only: ['resolve']),
        ];
    }

    public function index()
    {
        // Pending Material Requests
        $pendingRequestQuery = MaterialRequest::with([
            'user',
            'items.material',
        ])->where('status', 'pending');

        if (! auth()->user()->hasAnyRole(['Super Admin', 'Admin'])) {
            $pendingRequestQuery->where('requested_by', auth()->id());
        }

        $pendingRequests = $pendingRequestQuery->latest()->get();

        // Base query for Material Dispatch
        $dispatchQuery = function ($status) {

            $query = MaterialDispatch::with([
                'request.user',
                'items.requestItem',
                'items.material',
            ])->where('status', $status);

            if (! auth()->user()->hasAnyRole(['Super Admin', 'Admin'])) {
                $query->whereHas('request', function ($q) {
                    $q->where('requested_by', auth()->id());
                });
            }

            return $query->latest()->get();
        };

        $approvedDispatches = $dispatchQuery('pending');
        $partialApprovedRequests = $dispatchQuery('partially_dispatched');
        $dispatched = $dispatchQuery('dispatched');
        $received = $dispatchQuery('completed');
        $discrepancy = $dispatchQuery('received_with_discrepancy');
        $rejected = $dispatchQuery('rejected');

        return view('stocks.material-dispatch.list', compact(
            'pendingRequests',
            'approvedDispatches',
            'partialApprovedRequests',
            'dispatched',
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
                    'material_request_id' => 'Only pending requests can be approved.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Create Dispatch
            |--------------------------------------------------------------------------
            */

            $dispatch = MaterialDispatch::create([

                'dispatch_no' => 'MD-'.str_pad(MaterialDispatch::count() + 1, 6, '0', STR_PAD_LEFT),

                'material_request_id' => $materialRequest->id,

                'dispatched_by' => Auth::id(),

                'dispatched_at' => now(),

                // temporary
                'status' => 'pending',

            ]);

            /*
            |--------------------------------------------------------------------------
            | Dispatch Items
            |--------------------------------------------------------------------------
            */

            foreach ($validated['items'] as $itemData) {

                $requestItem = $materialRequest->items()
                    ->where('id', $itemData['request_item_id'])
                    ->firstOrFail();

                $dispatchQty = (float) $itemData['dispatch_qty'];

                $requestedQty = (float) $requestItem->requested_qty;

                /*
                |--------------------------------------------------------------------------
                | Validation
                |--------------------------------------------------------------------------
                */

                if ($dispatchQty <= 0) {

                    throw ValidationException::withMessages([
                        'items' => 'Dispatch quantity must be greater than zero.',
                    ]);
                }

                if ($dispatchQty > $requestedQty) {

                    throw ValidationException::withMessages([
                        'items' => 'Dispatch quantity cannot exceed requested quantity.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Check Stock
                |--------------------------------------------------------------------------
                */

                $material = Material::lockForUpdate()
                    ->findOrFail($requestItem->material_id);

                if ($dispatchQty > $material->current_stock) {

                    throw ValidationException::withMessages([
                        'items' => "Insufficient stock for {$material->material_name}.",
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Deduct Stock
                |--------------------------------------------------------------------------
                */

                $material->decrement(
                    'current_stock',
                    $dispatchQty
                );

                StockLedger::add(
                    $material->id,
                    'dispatch',
                    MaterialDispatch::class,
                    $dispatch->id,
                    0,
                    $dispatchQty,
                    $material->fresh()->current_stock,
                    'Material Dispatched'
                );

                /*
                |--------------------------------------------------------------------------
                | Create Dispatch Item
                |--------------------------------------------------------------------------
                */

                $dispatch->items()->create([

                    'material_request_item_id' => $requestItem->id,

                    'material_id' => $requestItem->material_id,

                    'dispatched_qty' => $dispatchQty,

                    'received_qty' => 0,

                    'missing_qty' => $requestedQty - $dispatchQty,

                ]);
            }

            $dispatch->load('items.requestItem');

            $hasRemaining = $dispatch->items->contains(function ($item) {

                return (float) $item->dispatched_qty < (float) $item->requestItem->requested_qty;

            });

            $dispatch->update([

                'status' => $hasRemaining
                    ? 'partially_dispatched'
                    : 'dispatched',

            ]);
            /*
            |--------------------------------------------------------------------------
            | Update Material Request
            |--------------------------------------------------------------------------
            */

            $materialRequest->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
        });

        return redirect()
            ->route('material-dispatch.index')
            ->with([
                'message' => 'Material approved and dispatched successfully.',
                'alert-type' => 'success',
            ]);
    }

    //   Reject Material Request
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
                'dispatch_no' => 'MD-'.str_pad(MaterialDispatch::count() + 1, 6, '0', STR_PAD_LEFT),
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

        return redirect()->route('material-dispatch.index')->with('success', 'Material request rejected successfully.');
    }

    //   Dispatch Materials
    //   Handles both:
    //   1. Partial Dispatch
    //   2. Full Dispatch
    public function dispatch(DispatchMaterialRequest $request)
    {
        $dispatch = MaterialDispatch::findOrFail(
            $request->material_dispatch_id
        );

        if ($dispatch->status !== 'partially_dispatched') {
            return back()->with([
                'message' => 'Only partially dispatched requests can be dispatched.',
                'alert-type' => 'error',
            ]);
        }

        $dispatch->update([
            'status' => 'dispatched',
            'dispatched_by' => auth()->id(),
            'dispatched_at' => now(),
        ]);

        return redirect()
            ->route('material-dispatch.index')
            ->with([
                'message' => 'Request moved to Dispatched successfully.',
                'alert-type' => 'success',
            ]);
    }

    //   Receive dispatched materials
    public function receive(ReceiveMaterialRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {

            $dispatch = MaterialDispatch::lockForUpdate()
                ->findOrFail($validated['material_dispatch_id']);

            if ($dispatch->status !== 'dispatched') {

                throw ValidationException::withMessages([
                    'material_dispatch_id' => 'Only dispatched materials can be received.',
                ]);
            }

            foreach ($validated['items'] as $itemData) {

                $dispatchItem = MaterialDispatchItem::with([
                    'requestItem',
                    'material',
                ])
                    ->where('material_dispatch_id', $dispatch->id)
                    ->where('id', $itemData['id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $requestedQty =
                    (float) $dispatchItem->requestItem->requested_qty;

                $dispatchedQty =
                    (float) $dispatchItem->dispatched_qty;

                $receivedQty =
                    (float) $itemData['received_qty'];

                if ($receivedQty > $dispatchedQty) {

                    throw ValidationException::withMessages([
                        'items' => 'Received quantity cannot exceed dispatched quantity.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | YOUR BUSINESS FLOW
                |
                | Missing = Requested - Received
                |--------------------------------------------------------------------------
                */

                $missingQty = max(
                    0,
                    $requestedQty - $receivedQty
                );

                $dispatchItem->update([

                    'received_qty' => $receivedQty,

                    'missing_qty' => $missingQty,

                ]);

                StockLedger::add(
                    $dispatchItem->material_id,
                    'receive',
                    MaterialDispatch::class,
                    $dispatch->id,
                    $receivedQty,
                    0,
                    $dispatchItem->material->current_stock,
                    'Material Received'
                );
            }

            $dispatch->load('items');

            $hasMissing = $dispatch->items->contains(function ($item) {

                return (float) $item->missing_qty > 0;

            });

            $dispatch->update([

                'status' => $hasMissing
                    ? 'received_with_discrepancy'
                    : 'completed',

                'received_by' => Auth::id(),

                'received_at' => now(),

                'remarks' => $validated['remarks'] ?? null,

            ]);
        });

        return redirect()
            ->route('material-dispatch.index')
            ->with([
                'message' => 'Materials received successfully.',
                'alert-type' => 'success',
            ]);
    }

    //   Resolve Material Discrepancy
    public function resolve(ResolveDiscrepancyRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {

            $dispatch = MaterialDispatch::lockForUpdate()
                ->where('status', 'received_with_discrepancy')
                ->findOrFail(
                    $validated['material_dispatch_id']
                );

            /*
            |--------------------------------------------------------------------------
            | Keep missing quantity for history.
            | Only change dispatch status.
            |--------------------------------------------------------------------------
            */

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
