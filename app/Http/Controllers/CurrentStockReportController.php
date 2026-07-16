<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialCategory;
use Illuminate\Http\Request;

class CurrentStockReportController extends Controller
{

    public function index(Request $request)
    {
        $query = Material::with('category');

        if ($request->filled('material_id')) {
            $query->where('id', $request->material_id);
        }

        if ($request->filled('category_id')) {
            $query->where('material_category_id', $request->category_id);
        }

        $materials = $query->orderBy('material_name')->get();

        foreach ($materials as $material) {

            // Purchase
            $purchaseQuery = $material->purchaseItems();

            if ($request->filled('from_date')) {
                $purchaseQuery->whereHas('purchase', function ($q) use ($request) {
                    $q->whereDate('purchase_date', '>=', $request->from_date);
                });
            }

            if ($request->filled('to_date')) {
                $purchaseQuery->whereHas('purchase', function ($q) use ($request) {
                    $q->whereDate('purchase_date', '<=', $request->to_date);
                });
            }

            $purchased = $purchaseQuery->sum('quantity');


            // Dispatch
            $dispatchQuery = $material->dispatchItems();

            if ($request->filled('from_date')) {
                $dispatchQuery->whereHas('dispatch', function ($q) use ($request) {
                    $q->whereDate('dispatched_at', '>=', $request->from_date);
                });
            }

            if ($request->filled('to_date')) {
                $dispatchQuery->whereHas('dispatch', function ($q) use ($request) {
                    $q->whereDate('dispatched_at', '<=', $request->to_date);
                });
            }

            $dispatched = $dispatchQuery->sum('dispatched_qty');


            // Consumption
            $consumptionQuery = $material->consumptions();

            if ($request->filled('from_date')) {
                $consumptionQuery->whereDate('consumption_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $consumptionQuery->whereDate('consumption_date', '<=', $request->to_date);
            }

            $consumed = $consumptionQuery->sum('consumed_qty');


            // Wastage
            $wastageQuery = $material->wastages();

            if ($request->filled('from_date')) {
                $wastageQuery->whereDate('wastage_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $wastageQuery->whereDate('wastage_date', '<=', $request->to_date);
            }

            $wastage = $wastageQuery->sum('quantity');


            // Report Values

            $material->opening_stock = max(
                0,
                $material->current_stock + $dispatched - $purchased
            );

            $material->purchased = $purchased;

            $material->dispatched = $dispatched;

            $material->consumed = $consumed;

            $material->wastage = $wastage;

            $material->closing_stock = $material->current_stock;
        }

        $categories = MaterialCategory::where('status', 'Active')->orderBy('category_name')->get();

        return view('stocks.current-stock.list', compact(
            'materials',
            'categories'
        ));
    }
}
