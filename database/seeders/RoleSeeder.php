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

        // $caterer = Role::create(['name' => 'caterer']);
        // $catererpermissions = Permission::whereIn(
        //     'name',
        //     [

        //     ]
        // )
        //     ->get();
        // $caterer->syncPermissions($catererpermissions);
    }
}
