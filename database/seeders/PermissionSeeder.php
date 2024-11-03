<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Create the download-backup permission
        $downloadBackupPermission = Permission::firstOrCreate(['name' => 'download-backup']);

        // Assign it to the superadmin role
        $superAdminRole = Role::where('name', 'superadmin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($downloadBackupPermission);
        }
    }
}
