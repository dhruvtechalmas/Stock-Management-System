<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWastageRequest;
use App\Models\AppNotification;
use App\Models\MaterialConsumption;
use App\Models\MaterialDispatchItem;
use App\Models\StockLedger;
use App\Models\Wastage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WastageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [

            new Middleware('permission:wastage.index', only: ['index']),
            new Middleware('permission:wastage.create', only: ['create', 'store']),
            new Middleware('permission:wastage.view', only: ['show']),
            new Middleware('permission:wastage.edit', only: ['edit', 'update']),
            new Middleware('permission:wastage.delete', only: ['destroy']),

        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Wastage::with([
            'material',
            'recordedBy',
        ]);

        // Super Admin & Admin can see all records
        if (! auth()->user()->hasAnyRole(['Super Admin', 'Admin'])) {
            $query->where('recorded_by', auth()->id());
        }

        $wastages = $query
            ->latest()
            ->paginate(10);

        $dispatchItems = MaterialDispatchItem::with([
            'material',
            'dispatch',
            'consumptions' => function ($query) {
                $query->latest('id');
            },
        ])
            ->whereHas('dispatch', function ($query) {
                $query->where('status', 'completed');
            })
            ->get()
            ->map(function ($item) {

                $latestConsumption = $item->consumptions->first();

                $item->remaining_qty = $latestConsumption
                    ? $latestConsumption->remaining_qty
                    : $item->received_qty;

                return $item;
            });

        return view('stocks.wastages.list', compact(
            'wastages',
            'dispatchItems'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dispatchItems = MaterialDispatchItem::with([
            'material',
            'dispatch',
        ])
            ->withSum('consumptions', 'consumed_qty')
            ->withSum('consumptions', 'wastage_qty')
            ->whereHas('dispatch', function ($query) {
                $query->where('status', 'completed');
            })
            ->get()
            ->map(function ($item) {

                $item->remaining_qty =
                    $item->received_qty
                    - ($item->consumptions_sum_consumed_qty ?? 0);

                return $item;
            });

        return view('stocks.wastages.create', compact('dispatchItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWastageRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {

            $dispatchItem = MaterialDispatchItem::with('material')->lockForUpdate()->findOrFail($validated['material_dispatch_item_id']);

            $latestConsumption = MaterialConsumption::where('material_dispatch_item_id', $dispatchItem->id)->latest('id')->first();

            $remainingQty = $latestConsumption ? $latestConsumption->remaining_qty : $dispatchItem->received_qty;

            if ((float) $validated['quantity'] > (float) $remainingQty) {

                throw ValidationException::withMessages([
                    'quantity' => 'Wastage quantity cannot exceed remaining quantity 
                    ('.number_format($remainingQty, 3).' '.$dispatchItem->material->unit.').',
                ]);
            }

            // Reduce received quantity
            $dispatchItem->decrement('received_qty', $validated['quantity']);

            // Generate Wastage Number
            $nextId = (Wastage::max('id') ?? 0) + 1;

            $wastageNo = 'WS-'.str_pad($nextId, 6, '0', STR_PAD_LEFT);

            $wastage = Wastage::create([
                'wastage_no' => $wastageNo,
                'material_id' => $dispatchItem->material_id,
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'],
                'wastage_date' => $validated['wastage_date'],
                'recorded_by' => auth()->id(),

                'reference_type' => MaterialDispatchItem::class,
                'reference_id' => $dispatchItem->id,
            ]);

            StockLedger::add(
                $dispatchItem->material_id,
                'wastage',
                Wastage::class,
                $wastage->id,
                0,
                $validated['quantity'],
                $dispatchItem->material->current_stock,
                'Material Wastage'
            );

            AppNotification::send(
                null,
                'Admin',
                'Material Wastage Reported',
                'Kitchen staff '.auth()->user()->name.' reported wastage of '.number_format($validated['quantity'], 2).' units of '.$dispatchItem->material->material_name.'.'
            );
        });

        return redirect()->route('wastages.index')->with([
            'message' => 'Wastage recorded successfully!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Wastage $wastage)
    {
        $wastage->load(['material', 'recordedBy']);

        return view('stocks.wastages.view', compact('wastage'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wastage $wastage)
    {
        DB::transaction(function () use ($wastage) {

            if ($wastage->reference_id) {

                $dispatchItem = MaterialDispatchItem::lockForUpdate()->find($wastage->reference_id);

                if ($dispatchItem) {
                    $dispatchItem->increment('received_qty', $wastage->quantity);
                }
            }

            $wastage->delete();
        });

        return redirect()->route('wastages.index')->with([
            'message' => 'Wastage deleted successfully!',
            'alert-type' => 'success',
        ]);
    }
}
