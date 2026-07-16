<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Material;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockLedger;
use App\Models\Supplier;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [

            new Middleware('permission:purchase.index', only: ['index']),

            new Middleware('permission:purchase.create', only: ['create', 'store']),

            new Middleware('permission:purchase.view', only: ['show']),

            new Middleware('permission:purchase.edit', only: ['edit', 'update']),

            new Middleware('permission:purchase.delete', only: ['destroy']),

        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'user'])
            ->latest()
            ->paginate(10);

        $suppliers = Supplier::where('is_active', true)->get();

        $materials = Material::where('status', 'Active')->get();

        return view('stocks.purchase.list', compact(
            'purchases',
            'suppliers',
            'materials'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::where('is_active', 1)->get();

        $materials = Material::where('status', 'Active')->get();

        $purchaseNo = 'PUR-' . str_pad((Purchase::max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT);

        return view('stocks.purchase.create', compact(
            'suppliers',
            'materials',
            'purchaseNo'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        DB::transaction(function () use ($request) {

            $purchase = Purchase::create([
                'purchase_no' => 'PUR-' . str_pad(Purchase::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'supplier_id' => $request->supplier_id,
                'invoice_no' => $request->invoice_no,
                'purchase_date' => $request->purchase_date,
                'created_by' => auth()->id(),
                'total_amount' => 0,
            ]);

            $grandTotal = 0;

            foreach ($request->items as $item) {

                if ($item['quantity'] <= 0) {
                    abort(422, 'Quantity must be greater than zero.');
                }

                if ($item['unit_price'] <= 0) {
                    abort(422, 'Unit Price must be greater than zero.');
                }

                $lineTotal = $item['quantity'] * $item['unit_price'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $lineTotal,
                ]);

                $grandTotal += $lineTotal;
            }

            $purchase->update([
                'total_amount' => $grandTotal,
            ]);

        });

        return redirect()->route('purchases.index')->with([
            'message' => 'Purchase created successfully.',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load([
            'supplier',
            'user',
            'items.material',
        ]);

        return view('stocks.purchase.view', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $purchase->load('items.material');

        $suppliers = Supplier::where('is_active', true)
            ->orderBy('name')
            ->get();

        $materials = Material::where('status', 'Active')
            ->orderBy('material_name')
            ->get();

        return view('stocks.purchase.edit', compact(
            'purchase',
            'suppliers',
            'materials'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::transaction(function () use ($request, $purchase) {

            // Reverse old stock
            foreach ($purchase->items as $item) {

                $material = Material::find($item->material_id);

                if ($material) {
                    $material->decrement('current_stock', $item->quantity);
                }
            }

            // Delete old purchase items
            $purchase->items()->delete();

            // Update purchase
            $purchase->update([
                'purchase_no' => $purchase->purchase_no,
                'supplier_id' => $request->supplier_id,
                'invoice_no' => $request->invoice_no,
                'purchase_date' => Carbon::createFromFormat('d M Y', $request->purchase_date)
                    ->format('Y-m-d'),
                'total_amount' => 0,
            ]);

            $grandTotal = 0;

            // Save new items
            foreach ($request->items as $item) {

                if ($item['quantity'] <= 0) {
                    return back()->withErrors([
                        'items' => 'Quantity must be greater than zero.',
                    ])->withInput();
                }

                if ($item['unit_price'] <= 0) {
                    return back()->withErrors([
                        'items' => 'Unit price must be greater than zero.',
                    ])->withInput();
                }

                $lineTotal = $item['quantity'] * $item['unit_price'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $lineTotal,
                ]);

                // Add stock again
                $material = Material::find($item['material_id']);

                $material->increment('current_stock', $item['quantity']);

                StockLedger::add(
                    $material->id,
                    'purchase',
                    Purchase::class,
                    $purchase->id,
                    $item['quantity'],
                    0,
                    $material->fresh()->current_stock,
                    'Purchase Entry'
                );

                $grandTotal += $lineTotal;
            }

            // Update total amount
            $purchase->update([
                'total_amount' => $grandTotal,
            ]);

        });

        return redirect()->route('purchases.index')->with([
            'message' => 'Purchase updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {

            foreach ($purchase->items as $item) {

                $material = Material::find($item->material_id);

                if ($material) {
                    $material->decrement('current_stock', $item->quantity);
                }
            }

            $purchase->delete();
        });

        return redirect()->route('purchases.index')->with([
            'message' => 'Purchase deleted successfully.',
            'alert-type' => 'success',
        ]);
    }
}
