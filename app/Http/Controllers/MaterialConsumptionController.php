<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialConsumptionRequest;
use App\Models\MaterialConsumption;
use App\Models\MaterialDispatchItem;
use App\Models\StockLedger;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MaterialConsumptionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:material-consumption.index', only: ['index']),
            new Middleware('permission:material-consumption.create', only: ['store']),
        ];
    }

    public function index()
    {
        $dispatchItems = MaterialDispatchItem::with([
            'material',
            'dispatch',
        ])
            ->withSum('consumptions', 'consumed_qty')
            ->where('received_qty', '>', 0)
            ->whereHas('dispatch', function ($query) {

                $query->where('status', 'completed');

                // Only own dispatches for normal users
                if (! auth()->user()->hasAnyRole(['Super Admin', 'Admin'])) {
                    $query->where('dispatched_by', auth()->id());
                }

            })->latest()->get()->map(function ($item) {

                $item->remaining_qty = (float) $item->received_qty - (float) ($item->consumptions_sum_consumed_qty ?? 0);

                return $item;

            });

        $consumptionQuery = MaterialConsumption::with([
            'material',
            'dispatchItem.dispatch',
            'recordedBy',
        ]);

        // Only own consumption records for normal users
        if (! auth()->user()->hasAnyRole(['Super Admin', 'Admin'])) {
            $consumptionQuery->where('recorded_by', auth()->id());
        }

        $consumptions = $consumptionQuery
            ->latest('consumption_date')
            ->get();

        return view('stocks.material-consumption.list', compact(
            'dispatchItems',
            'consumptions'
        ));
    }

    public function store(StoreMaterialConsumptionRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {

            $dispatchItem = MaterialDispatchItem::with('dispatch')->lockForUpdate()->findOrFail(
                $validated['material_dispatch_item_id']
            );

            // Check correct material
            if ((int) $dispatchItem->material_id !== (int) $validated['material_id']) {
                throw ValidationException::withMessages([
                    'material_id' => 'The selected material does not match the dispatch item.',
                ]);
            }

            // Only completed received materials can be consumed
            if ($dispatchItem->dispatch->status !== 'completed') {

                throw ValidationException::withMessages([
                    'material_dispatch_item_id' => 'This material is not yet available for consumption.',
                ]);
            }

            $receivedQty = (float) $dispatchItem->received_qty;

            $alreadyConsumed = (float) MaterialConsumption::where(
                'material_dispatch_item_id',
                $dispatchItem->id
            )->sum('consumed_qty');

            $remainingQty = $receivedQty - $alreadyConsumed;

            $newRemainingQty = $remainingQty - (float) $validated['consumed_qty'];

            if ($remainingQty <= 0) {

                throw ValidationException::withMessages([
                    'consumed_qty' => 'This material has already been fully consumed.',
                ]);
            }

            if (
                (float) $validated['consumed_qty'] > $remainingQty
            ) {

                throw ValidationException::withMessages([
                    'consumed_qty' => 'Consumed quantity cannot exceed the available quantity of '.number_format($remainingQty, 3).'.',
                ]);
            }

            $consumption = MaterialConsumption::create([
                'material_dispatch_item_id' => $dispatchItem->id,

                'material_id' => $dispatchItem->material_id,

                'consumed_qty' => $validated['consumed_qty'],

                'remaining_qty' => $newRemainingQty,

                'consumption_date' => $validated['consumption_date'],

                'recorded_by' => auth()->id(),
            ]);

            StockLedger::add(
                $dispatchItem->material_id,
                'consumption',
                MaterialConsumption::class,
                $consumption->id,
                0,
                $validated['consumed_qty'],
                $dispatchItem->material->current_stock,
                'Material Consumed'
            );
        });

        return redirect()->route('material-consumption.index')->with([
            'message' => 'Material consumption recorded successfully.',
            'alert-type' => 'success',
        ]);
    }
}
