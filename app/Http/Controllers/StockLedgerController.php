<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\StockLedger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StockLedgerController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:report.stock-ledger', only: ['index']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StockLedger::with(['material', 'createdBy']);

        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }

        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('transaction_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('transaction_date', '<=', $request->to_date);
        }

        $ledgers = $query->orderByDesc('transaction_date')->orderByDesc('id')->paginate(15)->withQueryString();

        $materials = Material::orderBy('material_name')->get(); // Use your correct column
        $users = User::orderBy('name')->get();

        return view('stocks.stock-ledger.list', compact('ledgers', 'materials', 'users'));
    }

  
}
