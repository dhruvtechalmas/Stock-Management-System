<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveMaterialDispatchRequest;
use App\Http\Requests\DispatchMaterialRequest;
use App\Http\Requests\ReceiveMaterialRequest;
use App\Http\Requests\RejectMaterialDispatchRequest;
use App\Http\Requests\ResolveDiscrepancyRequest;
use App\Models\MaterialDispatch;
use App\Models\MaterialDispatchItem;
use App\Models\MaterialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialDispatchController extends Controller
{
    /**
     * Display Material Dispatch Dashboard
     */
    public function index()
    {
        // Pending Material Requests
        $pendingRequests = MaterialRequest::with([
            'user',
            'items.material'
        ])
            ->where('status', 'approved')
            ->latest()
            ->get();

        // Approved Dispatches
        $approvedDispatches = MaterialDispatch::with([
            'request.user',
            'items.material'
        ])
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Partially Approved
        $partialDispatches = MaterialDispatch::with([
            'request.user',
            'items.material'
        ])
            ->where('status', 'partially_approved')
            ->latest()
            ->get();

        // Dispatched
        $dispatched = MaterialDispatch::with([
            'request.user',
            'items.material'
        ])
            ->where('status', 'dispatched')
            ->latest()
            ->get();

        // Received
        $received = MaterialDispatch::with([
            'request.user',
            'items.material'
        ])
            ->where('status', 'received')
            ->latest()
            ->get();

        // Discrepancy
        $discrepancy = MaterialDispatch::with([
            'request.user',
            'items.material'
        ])
            ->where('status', 'received_with_discrepancy')
            ->latest()
            ->get();

        // Rejected
        $rejected = MaterialDispatch::with([
            'request.user'
        ])
            ->where('status', 'rejected')
            ->latest()
            ->get();

        return view('stocks.material-dispatch.list', compact(
            'pendingRequests',
            'approvedDispatches',
            'partialDispatches',
            'dispatched',
            'received',
            'discrepancy',
            'rejected'
        ));
    }

    /**
     * Approve Material Request
     */
    public function approve(ApproveMaterialDispatchRequest $request)
    {
        DB::beginTransaction();

        try {

            // Get Material Request
            $materialRequest = MaterialRequest::with('items')
                ->findOrFail($request->material_request_id);

            // Prevent duplicate dispatch
            if (MaterialDispatch::where('material_request_id', $materialRequest->id)->exists()) {

                return back()->with('error', 'Dispatch already created for this request.');
            }

            // Create Dispatch
            $dispatch = MaterialDispatch::create([
                'dispatch_no' => 'MD-' . now()->format('YmdHis'),
                'material_request_id' => $materialRequest->id,
                'dispatched_by' => Auth::id(),
                'status' => 'pending',
            ]);

            // Copy Request Items into Dispatch Items
            foreach ($materialRequest->items as $item) {

                MaterialDispatchItem::create([
                    'material_dispatch_id' => $dispatch->id,
                    'material_request_item_id' => $item->id,
                    'material_id' => $item->material_id,
                    'dispatched_qty' => $item->requested_qty,
                    'received_qty' => 0,
                    'missing_qty' => 0,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('material-dispatch.index')
                ->with('success', 'Material Dispatch approved successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
    /**
     * Reject Material Request
     */
    public function reject(RejectMaterialDispatchRequest $request)
    {
        DB::beginTransaction();

        try {

            // Get Material Request
            $materialRequest = MaterialRequest::findOrFail(
                $request->material_request_id
            );

            // Update Material Request
            $materialRequest->update([
                'status' => 'rejected',
                'reject_reason' => $request->reason,
            ]);

            // If Dispatch Exists, Update Dispatch Status
            MaterialDispatch::where('material_request_id', $materialRequest->id)
                ->update([
                    'status' => 'rejected',
                    'remarks' => $request->reason,
                ]);

            DB::commit();

            return redirect()
                ->route('material-dispatch.index')
                ->with('success', 'Material Request rejected successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Dispatch Materials
     */
    public function dispatch(DispatchMaterialRequest $request)
    {
        DB::beginTransaction();

        try {

            DB::commit();

            return back()
                ->with('success', 'Materials dispatched successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Receive Materials
     */
    public function receive(ReceiveMaterialRequest $request)
    {
        DB::beginTransaction();

        try {

            DB::commit();

            return back()
                ->with('success', 'Materials received successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
    
    /**
     * Resolve Discrepancy
     */
    public function resolve(ResolveDiscrepancyRequest $request)
    {
        DB::beginTransaction();

        try {

            DB::commit();

            return back()
                ->with('success', 'Discrepancy resolved successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * View Dispatch Details
     */
    // public function show(MaterialDispatch $materialDispatch)
    // {
    //     $materialDispatch->load([
    //         'request.user',
    //         'items.material',
    //         'dispatcher'
    //     ]);

    //     return view('stocks.material-dispatch.show', compact('materialDispatch'));
    // }
}