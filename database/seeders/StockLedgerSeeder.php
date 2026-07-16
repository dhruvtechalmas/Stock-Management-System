<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\StockLedger;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StockLedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure at least some Users exist
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Demo Administrator',
                'email' => 'admin@example.com',
            ]);
            User::factory()->create([
                'name' => 'John Kitchen Staff',
                'email' => 'kitchen@example.com',
            ]);
        }

        $users = User::all();
        $adminUser = $users->first();
        $staffUser = $users->last();

        // 2. Ensure at least some Material Categories exist
        $produceCategory = MaterialCategory::firstOrCreate(
            ['category_name' => 'Vegetables & Produce'],
            ['status' => 'Active']
        );
        $dairyCategory = MaterialCategory::firstOrCreate(
            ['category_name' => 'Dairy & Poultry'],
            ['status' => 'Active']
        );
        $packagingCategory = MaterialCategory::firstOrCreate(
            ['category_name' => 'Packaging Materials'],
            ['status' => 'Active']
        );

        // 3. Ensure at least some Materials exist
        $tomatoes = Material::firstOrCreate(
            ['material_name' => 'Fresh Tomatoes'],
            [
                'material_category_id' => $produceCategory->id,
                'unit' => 'kg',
                'current_stock' => 150,
                'minimum_stock' => 50,
                'status' => 'Active',
            ]
        );
        $cheese = Material::firstOrCreate(
            ['material_name' => 'Mozzarella Cheese'],
            [
                'material_category_id' => $dairyCategory->id,
                'unit' => 'kg',
                'current_stock' => 85,
                'minimum_stock' => 20,
                'status' => 'Active',
            ]
        );
        $boxes = Material::firstOrCreate(
            ['material_name' => 'Pizza Box (Medium)'],
            [
                'material_category_id' => $packagingCategory->id,
                'unit' => 'pcs',
                'current_stock' => 500,
                'minimum_stock' => 100,
                'status' => 'Active',
            ]
        );

        // Clear existing ledgers to start fresh
        StockLedger::truncate();

        // 4. Seed Stock Ledgers for Fresh Tomatoes
        $this->seedLedgerForMaterial($tomatoes, [
            [
                'date' => Carbon::now()->subDays(10),
                'type' => 'purchase',
                'qty_in' => 100.0,
                'qty_out' => 0.0,
                'user' => $adminUser,
                'remarks' => 'Initial bulk purchase from Supplier A',
            ],
            [
                'date' => Carbon::now()->subDays(8),
                'type' => 'dispatch',
                'qty_in' => 0.0,
                'qty_out' => 30.0,
                'user' => $adminUser,
                'remarks' => 'Dispatched to Central Kitchen',
            ],
            [
                'date' => Carbon::now()->subDays(7),
                'type' => 'consumption',
                'qty_in' => 0.0,
                'qty_out' => 25.0,
                'user' => $staffUser,
                'remarks' => 'Daily pizza sauce preparation',
            ],
            [
                'date' => Carbon::now()->subDays(5),
                'type' => 'purchase',
                'qty_in' => 120.0,
                'qty_out' => 0.0,
                'user' => $adminUser,
                'remarks' => 'Restock from local organic farm',
            ],
            [
                'date' => Carbon::now()->subDays(3),
                'type' => 'wastage',
                'qty_in' => 0.0,
                'qty_out' => 15.0,
                'user' => $staffUser,
                'remarks' => 'Spoiled tomatoes discarded',
            ],
        ]);

        // Seed Stock Ledgers for Mozzarella Cheese
        $this->seedLedgerForMaterial($cheese, [
            [
                'date' => Carbon::now()->subDays(12),
                'type' => 'purchase',
                'qty_in' => 50.0,
                'qty_out' => 0.0,
                'user' => $adminUser,
                'remarks' => 'Imported Mozzarella batch',
            ],
            [
                'date' => Carbon::now()->subDays(9),
                'type' => 'consumption',
                'qty_in' => 0.0,
                'qty_out' => 15.0,
                'user' => $staffUser,
                'remarks' => 'Used for weekend pizzas',
            ],
            [
                'date' => Carbon::now()->subDays(6),
                'type' => 'receive',
                'qty_in' => 60.0,
                'qty_out' => 0.0,
                'user' => $staffUser,
                'remarks' => 'Received from Cold Storage Unit 2',
            ],
            [
                'date' => Carbon::now()->subDays(2),
                'type' => 'wastage',
                'qty_in' => 0.0,
                'qty_out' => 5.0,
                'user' => $staffUser,
                'remarks' => 'Mold contamination wastage',
            ],
            [
                'date' => Carbon::now()->subDays(1),
                'type' => 'adjustment',
                'qty_in' => 0.0,
                'qty_out' => 2.0,
                'user' => $adminUser,
                'remarks' => 'Physical stock count correction',
            ],
        ]);

        // Seed Stock Ledgers for Pizza Boxes
        $this->seedLedgerForMaterial($boxes, [
            [
                'date' => Carbon::now()->subDays(15),
                'type' => 'purchase',
                'qty_in' => 1000.0,
                'qty_out' => 0.0,
                'user' => $adminUser,
                'remarks' => 'Bulk package order from BoxCo',
            ],
            [
                'date' => Carbon::now()->subDays(10),
                'type' => 'dispatch',
                'qty_in' => 0.0,
                'qty_out' => 300.0,
                'user' => $adminUser,
                'remarks' => 'Dispatched to Delivery Outlet 1',
            ],
            [
                'date' => Carbon::now()->subDays(5),
                'type' => 'dispatch',
                'qty_in' => 0.0,
                'qty_out' => 200.0,
                'user' => $adminUser,
                'remarks' => 'Dispatched to Delivery Outlet 2',
            ],
        ]);
    }

    /**
     * Helper to seed sequential stock ledgers for a material.
     */
    private function seedLedgerForMaterial(Material $material, array $transactions): void
    {
        $currentBalance = 0.0;

        foreach ($transactions as $tx) {
            $qtyIn = (float) $tx['qty_in'];
            $qtyOut = (float) $tx['qty_out'];
            $currentBalance = $currentBalance + $qtyIn - $qtyOut;

            StockLedger::create([
                'material_id' => $material->id,
                'transaction_type' => $tx['type'],
                // Polymorphic relationship fields
                'reference_type' => Material::class,
                'reference_id' => $material->id,
                'qty_in' => $qtyIn,
                'qty_out' => $qtyOut,
                'balance_after' => $currentBalance,
                'transaction_date' => $tx['date']->format('Y-m-d'),
                'remarks' => $tx['remarks'],
                'created_by' => $tx['user']->id,
            ]);
        }

        // Keep the material's current_stock in sync
        $material->update([
            'current_stock' => $currentBalance,
        ]);
    }
}
