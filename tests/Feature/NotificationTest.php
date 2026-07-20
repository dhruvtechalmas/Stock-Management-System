<?php

use App\Events\NotificationSent;
use App\Models\AppNotification;
use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\MaterialDispatch;
use App\Models\MaterialRequest;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

test('trigger notification helper broadcasts event', function () {
    Event::fake();

    $user = User::factory()->create();

    $notification = AppNotification::send(
        $user->id,
        null,
        'Test Alert',
        'This is a test notification.'
    );

    $this->assertDatabaseHas('app_notifications', [
        'id' => $notification->id,
        'user_id' => $user->id,
        'title' => 'Test Alert',
        'is_read' => false,
    ]);

    Event::assertDispatched(NotificationSent::class, function ($event) use ($notification) {
        return $event->notification->id === $notification->id;
    });
});

test('user can fetch notifications via API', function () {
    $role = Role::findOrCreate('Kitchen Staff', 'web');
    $user = User::factory()->create();
    $user->assignRole($role);

    AppNotification::send($user->id, null, 'Personal Alert', 'For your eyes only');
    AppNotification::send(null, 'Kitchen Staff', 'Role Alert', 'For all kitchen staff');
    AppNotification::send(null, 'Admin', 'Admin Alert', 'Should not see this');

    $response = $this->actingAs($user)
        ->getJson(route('api.notifications.index'))
        ->assertSuccessful();

    $data = $response->json();

    // Should see Personal Alert and Role Alert, but not Admin Alert
    $this->assertCount(2, $data);
    $this->assertEquals('Personal Alert', $data[0]['title']);
    $this->assertEquals('Role Alert', $data[1]['title']);
});

test('admin user can fetch all notifications via API', function () {
    $role = Role::findOrCreate('Admin', 'web');
    $user = User::factory()->create();
    $user->assignRole($role);

    AppNotification::send($user->id, null, 'Personal Alert', 'For your eyes only');
    AppNotification::send(null, 'Kitchen Staff', 'Kitchen Alert', 'For all kitchen staff');
    AppNotification::send(null, 'Admin', 'Admin Alert', 'For admin staff');

    $response = $this->actingAs($user)
        ->getJson(route('api.notifications.index'))
        ->assertSuccessful();

    $data = $response->json();

    // Admin should see all 3 notifications
    $this->assertCount(3, $data);
});

test('user can mark notification as read', function () {
    $user = User::factory()->create();

    $notification = AppNotification::send($user->id, null, 'Alert', 'Alert message');

    $this->actingAs($user)
        ->postJson(route('api.notifications.read', $notification->id))
        ->assertSuccessful();

    $this->assertTrue($notification->fresh()->is_read);
});

test('user can mark all notifications as read', function () {
    $role = Role::findOrCreate('Kitchen Staff', 'web');
    $user = User::factory()->create();
    $user->assignRole($role);

    AppNotification::send($user->id, null, 'Alert 1', 'Message 1');
    AppNotification::send(null, 'Kitchen Staff', 'Alert 2', 'Message 2');

    $this->actingAs($user)
        ->postJson(route('api.notifications.read-all'))
        ->assertSuccessful();

    $this->assertDatabaseMissing('app_notifications', [
        'is_read' => false,
    ]);
});

test('user can access notifications history page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('notifications.history'))
        ->assertSuccessful()
        ->assertViewIs('stocks.notifications.history')
        ->assertViewHas('notifications');
});

test('material update and delete triggers notification', function () {
    $admin = User::factory()->create();
    $role = Role::findOrCreate('Super Admin', 'web');
    $admin->assignRole($role);

    $role->givePermissionTo(Permission::findOrCreate('material.edit', 'web'));
    $role->givePermissionTo(Permission::findOrCreate('material.delete', 'web'));

    $material = Material::create([
        'material_name' => 'Salt',
        'material_category_id' => MaterialCategory::create(['category_name' => 'Spices', 'status' => 'Active'])->id,
        'unit' => 'Kg',
        'current_stock' => 10,
        'minimum_stock' => 2,
        'status' => 'Active',
    ]);

    $this->actingAs($admin)
        ->put(route('materials.update', $material->id), [
            'material_name' => 'Salt iodized',
            'material_category_id' => $material->material_category_id,
            'unit' => 'Kg',
            'current_stock' => 10,
            'minimum_stock' => 2,
            'status' => 'Active',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('app_notifications', [
        'target_role' => 'Kitchen Staff',
        'title' => 'Material Updated',
    ]);

    $this->actingAs($admin)
        ->delete(route('materials.destroy', $material->id))
        ->assertRedirect();

    $this->assertDatabaseHas('app_notifications', [
        'target_role' => 'Kitchen Staff',
        'title' => 'Material Deleted',
    ]);
});

test('material request update and delete triggers notification', function () {
    $kitchen = User::factory()->create();
    $role = Role::findOrCreate('Kitchen Staff', 'web');
    $kitchen->assignRole($role);

    $role->givePermissionTo(Permission::findOrCreate('material-request.edit', 'web'));
    $role->givePermissionTo(Permission::findOrCreate('material-request.delete', 'web'));

    $request = MaterialRequest::create([
        'request_no' => 'MR-000001',
        'requested_by' => $kitchen->id,
        'request_date' => now()->format('Y-m-d'),
        'status' => 'pending',
    ]);

    $material = Material::create([
        'material_name' => 'Rice',
        'material_category_id' => MaterialCategory::create(['category_name' => 'Grain', 'status' => 'Active'])->id,
        'unit' => 'Kg',
        'current_stock' => 10,
        'minimum_stock' => 2,
        'status' => 'Active',
    ]);

    $this->actingAs($kitchen)
        ->put(route('material-requests.update', $request->id), [
            'request_date' => now()->format('d M Y'),
            'remarks' => 'Updated request remarks',
            'items' => [
                [
                    'material_id' => $material->id,
                    'requested_qty' => 5,
                ],
            ],
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('app_notifications', [
        'target_role' => 'Admin',
        'title' => 'Material Request Updated',
    ]);

    $this->actingAs($kitchen)
        ->delete(route('material-requests.destroy', $request->id))
        ->assertRedirect();

    $this->assertDatabaseHas('app_notifications', [
        'target_role' => 'Admin',
        'title' => 'Material Request Cancelled',
    ]);
});

test('dispatch receive and resolve triggers notification', function () {
    $kitchen = User::factory()->create();
    $role = Role::findOrCreate('Kitchen Staff', 'web');
    $kitchen->assignRole($role);

    $role->givePermissionTo(Permission::findOrCreate('material-dispatch.receive', 'web'));

    $material = Material::create([
        'material_name' => 'Sugar',
        'material_category_id' => MaterialCategory::create(['category_name' => 'Baking', 'status' => 'Active'])->id,
        'unit' => 'Kg',
        'current_stock' => 10,
        'minimum_stock' => 2,
        'status' => 'Active',
    ]);

    $request = MaterialRequest::create([
        'request_no' => 'MR-000002',
        'requested_by' => $kitchen->id,
        'request_date' => now()->format('Y-m-d'),
        'status' => 'approved',
    ]);

    $requestItem = $request->items()->create([
        'material_id' => $material->id,
        'requested_qty' => 5,
    ]);

    $dispatch = MaterialDispatch::create([
        'dispatch_no' => 'MD-000001',
        'material_request_id' => $request->id,
        'dispatched_by' => User::factory()->create()->id,
        'dispatched_at' => now(),
        'status' => 'dispatched',
    ]);

    $dispatchItem = $dispatch->items()->create([
        'material_request_item_id' => $requestItem->id,
        'material_id' => $material->id,
        'dispatched_qty' => 5,
        'received_qty' => 0,
        'missing_qty' => 5,
    ]);

    $this->actingAs($kitchen)
        ->post(route('material-dispatch.receive'), [
            'material_dispatch_id' => $dispatch->id,
            'items' => [
                [
                    'id' => $dispatchItem->id,
                    'received_qty' => 5,
                ],
            ],
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('app_notifications', [
        'target_role' => 'Admin',
        'title' => 'Dispatch Received',
    ]);
});
