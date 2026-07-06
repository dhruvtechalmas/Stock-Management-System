<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [    

            // Dashboard
            'dashboard.view',

            // Material Category
            'material-category.index',
            'material-category.create',
            'material-category.view',
            'material-category.edit',
            'material-category.delete',

            // Material
            'material.index',
            'material.create',
            'material.view',
            'material.edit',
            'material.delete',

            // Supplier
            'supplier.index',
            'supplier.create',
            'supplier.view',
            'supplier.edit',
            'supplier.delete',

            // Purchase
            'purchase.index',
            'purchase.create',
            'purchase.view',
            'purchase.edit',
            'purchase.delete',

            // Material Request
            'material-request.index',
            'material-request.create',
            'material-request.view',
            'material-request.edit',
            'material-request.delete',

            // Material Dispatch
            'material-dispatch.index',
            'material-dispatch.create',
            'material-dispatch.view',
            'material-dispatch.edit',
            'material-dispatch.delete',

            // Material Consumption
            'material-consumption.index',
            'material-consumption.create',
            'material-consumption.view',
            'material-consumption.edit',
            'material-consumption.delete',

            // Wastage
            'wastage.index',
            'wastage.create',
            'wastage.view',
            'wastage.edit',
            'wastage.delete',

            // Reports
            'report.current-stock',
            'report.stock-ledger',
            'report.stock-report',

            // Users
            'user.index',
            'user.create',
            'user.view',
            'user.edit',
            'user.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Create Roles
        $admin = Role::findOrCreate('Admin', 'web');
        $kitchenStaff = Role::findOrCreate('Kitchen Staff', 'web');

        // Admin gets all permissions
        $admin->givePermissionTo(Permission::all());

        // Kitchen Staff Permissions
        $kitchenStaff->givePermissionTo([

            'dashboard.view',

            // Material
            'material.index',
            'material.view',

            // Material Category
            'material-category.index',
            'material-category.view',

            // Material Request
            'material-request.index',
            'material-request.create',
            'material-request.view',

            // Material Consumption
            'material-consumption.index',
            'material-consumption.create',
            'material-consumption.view',

            // Current Stock Report
            'report.current-stock',
        ]);
    }
}