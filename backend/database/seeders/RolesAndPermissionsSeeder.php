<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{
    $permissions = [
        'user.create',
        'user.view',
        'user.update',
        'user.delete',
    ];

    foreach ($permissions as $perm) {
        Permission::firstOrCreate(['name' => $perm]);
    }

    Role::firstOrCreate(['name' => 'admin'])
        ->givePermissionTo(Permission::all());

    Role::firstOrCreate(['name' => 'student'])
        ->givePermissionTo(['user.view']);
}
}
