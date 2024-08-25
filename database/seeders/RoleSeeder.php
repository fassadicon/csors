<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artisan::call('shield:generate --all');

        $caterer = Role::create(['name' => 'caterer']);
        $catererpermissions = Permission::whereIn(
            'name',
            [
                'view_order',
                'view_any_order',
                'create_order',
                'update_order',
                'restore_order',
                'restore_any_order',
                'replicate_order',
                'reorder_order',
                'delete_order',
                'delete_any_order',
                'view_event',
                'view_any_event',
                'create_event',
                'update_event',
                'restore_event',
                'restore_any_event',
                'replicate_event',
                'reorder_event',
                'delete_event',
                'delete_any_event',
                'view_service',
                'view_any_service',
                'create_service',
                'update_service',
                'restore_service',
                'restore_any_service',
                'replicate_service',
                'reorder_service',
                'delete_service',
                'delete_any_service',
                'view_serving::type',
                'view_any_serving::type',
                'create_serving::type',
                'update_serving::type',
                'restore_serving::type',
                'restore_any_serving::type',
                'replicate_serving::type',
                'reorder_serving::type',
                'delete_serving::type',
                'delete_any_serving::type',
            ]
        )
            ->get();
        $caterer->syncPermissions($catererpermissions);
    }
}
