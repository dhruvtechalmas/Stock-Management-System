<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\StockLedger;
use App\Models\User;
use Illuminate\Http\Request;

class StockLedgerController extends Controller
{
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

        $ledgers = $query->latest('transaction_date')->paginate(15)->withQueryString();

        $materials = Material::orderBy('material_name')->get(); // Use your correct column
        $users = User::orderBy('name')->get();

        return view('stocks.stock-ledger.list', compact('ledgers', 'materials', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StockLedger $stockLedger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockLedger $stockLedger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockLedger $stockLedger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockLedger $stockLedger)
    {
        //
    }
}
