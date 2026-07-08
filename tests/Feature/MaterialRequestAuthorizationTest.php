<?php

use App\Models\MaterialRequest;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

test('admin can view a material request', function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $admin = Role::create([
        'name' => 'Admin',
        'guard_name' => 'web',
    ]);

    $admin->givePermissionTo(Permission::create([
        'name' => 'material-request.view',
        'guard_name' => 'web',
    ]));

    $user = User::factory()->create();
    $user->assignRole($admin);

    $materialRequest = MaterialRequest::create([
        'request_no' => 'MR-000001',
        'requested_by' => $user->id,
        'request_date' => now()->toDateString(),
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->get(route('material-requests.show', $materialRequest))
        ->assertSuccessful()
        ->assertViewIs('stocks.material-request.view');
});
