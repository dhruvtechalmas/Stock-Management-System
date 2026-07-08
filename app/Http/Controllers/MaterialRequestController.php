<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequestRequest;
use App\Http\Requests\UpdateMaterialRequestRequest;
use App\Models\Material;
use App\Models\MaterialRequest;
use DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class MaterialRequestController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:material-request.index', only: ['index']),
            new Middleware('permission:material-request.create', only: ['create', 'store']),
            new Middleware('permission:material-request.view', only: ['show']),
            new Middleware('permission:material-request.edit', only: ['edit', 'update']),
            new Middleware('permission:material-request.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materialRequests = MaterialRequest::with([
            'user',
            'items.material',
        ])
            ->latest()
            ->paginate(10);

        $materials = Material::orderBy('material_name')->get();

        return view('stocks.material-request.list', compact(
            'materialRequests',
            'materials'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::orderBy('material_name')->get();

        $requestNo = 'MR-' . str_pad((MaterialRequest::max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT);

        return view('stocks.material-request.create', compact('materials', 'requestNo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaterialRequestRequest $request)
    {
        DB::transaction(function () use ($request) {

            $materialRequest = MaterialRequest::create([
                'request_no' => 'MR-' . str_pad((MaterialRequest::max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT),
                'requested_by' => Auth::id(),
                'request_date' => $request->request_date,
                'status' => 'pending',
                'remarks' => $request->remarks,
            ]);

            foreach ($request->items as $item) {

                $materialRequest->items()->create([
                    'material_id' => $item['material_id'],
                    'requested_qty' => $item['requested_qty'],
                ]);

            }

        });

        return redirect()->route('material-requests.index')->with([
            'message' => 'Material Request created successfully.',
            'alert-type' => 'success',
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaterialRequest $materialRequest)
    {
        $materialRequest->load('items.material');

        $materials = Material::orderBy('material_name')->get();

        return view('stocks.material-request.edit', compact(
            'materialRequest',
            'materials'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaterialRequestRequest $request, MaterialRequest $materialRequest)
    {

        DB::transaction(function () use ($request, $materialRequest) {

            $materialRequest->update([
                'request_date' => Carbon::createFromFormat('d M Y', $request->request_date)
                    ->format('Y-m-d'),
                'remarks' => $request->remarks,
            ]);

            // Remove old items
            $materialRequest->items()->delete();

            // Add new items
            foreach ($request->items as $item) {

                $materialRequest->items()->create([
                    'material_id' => $item['material_id'],
                    'requested_qty' => $item['requested_qty'],
                ]);

            }

        });

        return redirect()->route('material-requests.index')->with([
            'message' => 'Material Request updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaterialRequest $materialRequest)
    {
        $materialRequest->delete();

        return redirect()->route('material-requests.index')->with([
            'message' => 'Material Request deleted successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function show(MaterialRequest $materialRequest)
    {
        $materialRequest->load([
            'user',
            'items.material',
            'approvedBy',
        ]);

        return view(
            'stocks.material-request.view',
            compact('materialRequest')
        );
    }
    /**
     * Approve Material Request
     */
    public function approve(MaterialRequest $materialRequest)
    {
        if ($materialRequest->status != 'pending') {

            return back()->with([
                'message' => 'Only pending requests can be approved.',
                'alert-type' => 'warning',
            ]);

        }

        $materialRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with([
            'message' => 'Material Request approved successfully.',
            'alert-type' => 'success',
        ]);
    }


    /**
     * Reject Material Request
     */
    public function reject(MaterialRequest $materialRequest)
    {
        if ($materialRequest->status != 'pending') {

            return back()->with([
                'message' => 'Only pending requests can be rejected.',
                'alert-type' => 'warning',
            ]);

        }

        $materialRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with([
            'message' => 'Material Request rejected successfully.',
            'alert-type' => 'success',
        ]);
    }
}
