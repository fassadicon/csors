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
        $superadmin = Role::where(['name' => 'superadmin'])->first();
        $superadmin->revokePermissionTo('page_EditCatererPage');

        $caterer = Role::create(['name' => 'caterer']);
        $catererpermissions = Permission::whereNotIn(
            'name',
            [
                'view_role',
                'view_any_role',
                'create_role',
                'update_role',
                'delete_role',
                'delete_any_role',
                'view_caterer',
                'view_any_caterer',
                'create_caterer',
                'update_caterer',
                'restore_caterer',
                'restore_any_caterer',
                'replicate_caterer',
                'reorder_caterer',
                'delete_caterer',
                'delete_any_caterer',
                'widget_UsersOverview',
                'page_Backups',
                'page_BackupPage',
            ]
        )
            ->get();
        $caterer->syncPermissions($catererpermissions);

        Role::create(['name' => 'customer']);
    }
}
