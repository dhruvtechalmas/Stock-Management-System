<?php

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\MaterialRequest;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Wastage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

test('guest cannot access dashboard', function () {
    $this->get(route('stocks.index'))
        ->assertRedirect(route('login'));
});

test('admin can access dashboard and see admin metrics', function () {
    $adminRole = Role::findOrCreate('Admin', 'web');
    $user = User::factory()->create();
    $user->assignRole($adminRole);

    // Create seed data
    $category = MaterialCategory::create([
        'category_name' => 'Food Ingredients',
    ]);

    $material1 = Material::create([
        'material_name' => 'Flour',
        'material_category_id' => $category->id,
        'unit' => 'kg',
        'current_stock' => 10,
        'minimum_stock' => 20, // Low stock
    ]);

    $material2 = Material::create([
        'material_name' => 'Sugar',
        'material_category_id' => $category->id,
        'unit' => 'kg',
        'current_stock' => 50,
        'minimum_stock' => 10, // Good stock
    ]);

    $supplier = Supplier::create([
        'name' => 'Acme Supplies',
        'phone' => '12345678',
        'email' => 'acme@example.com',
    ]);

    $purchase = Purchase::create([
        'purchase_no' => 'PO-000001',
        'supplier_id' => $supplier->id,
        'purchase_date' => now()->toDateString(),
        'total_amount' => 150.00,
        'created_by' => $user->id,
    ]);

    $req = MaterialRequest::create([
        'request_no' => 'MR-000001',
        'requested_by' => $user->id,
        'request_date' => now()->toDateString(),
        'status' => 'pending',
    ]);

    Wastage::create([
        'wastage_no' => 'W-000001',
        'material_id' => $material1->id,
        'quantity' => 2.5,
        'reason' => 'Spill',
        'wastage_date' => now()->toDateString(),
        'recorded_by' => $user->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('stocks.index'))
        ->assertSuccessful()
        ->assertViewIs('stocks.index')
        ->assertViewHas([
            'isAdmin' => true,
            'totalMaterials' => 2,
            'totalSuppliers' => 1,
            'totalPurchases' => 1,
            'totalMaterialRequests' => 1,
            'lowStockCount' => 1, // flour is low stock
            'stockChartLabels',
            'stockChartValues',
            'categoryChartLabels',
            'categoryChartValues',
        ]);

    $response->assertSee('Flour');
    $response->assertSee('Acme Supplies');
    $response->assertSee('MR-000001');
    $response->assertSee('W-000001');
});

test('kitchen staff can access dashboard and see kitchen-specific metrics', function () {
    $kitchenRole = Role::findOrCreate('Kitchen Staff', 'web');
    $user = User::factory()->create();
    $user->assignRole($kitchenRole);

    $category = MaterialCategory::create([
        'category_name' => 'Food Ingredients',
    ]);

    $material = Material::create([
        'material_name' => 'Salt',
        'material_category_id' => $category->id,
        'unit' => 'kg',
        'current_stock' => 5,
        'minimum_stock' => 2,
    ]);

    $req = MaterialRequest::create([
        'request_no' => 'MR-000002',
        'requested_by' => $user->id,
        'request_date' => now()->toDateString(),
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)
        ->get(route('stocks.index'))
        ->assertSuccessful()
        ->assertViewIs('stocks.index')
        ->assertViewHas([
            'isAdmin' => false,
            'myRequestsCount' => 1,
            'pendingRequestsCount' => 1,
            'dispatchesPendingCount' => 0,
            'requestStatusChartLabels',
            'requestStatusChartValues',
            'consumptionChartLabels',
            'consumptionChartValues',
        ]);

    $response->assertSee('MR-000002');
    $response->assertDontSee('Recent Purchases');
});
