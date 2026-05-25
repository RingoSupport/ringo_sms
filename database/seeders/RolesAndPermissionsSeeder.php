<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view dashboard',

            'manage roles',

            'view wallets',
           'view wallet history',

            'view messages',

            'view reports',

            'view audit logs',

            'view users',
            'create users',
            'edit users',
            'delete users',

            'view clients',
            'create clients',
            'edit clients',
            'disable clients',
            'view messages',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
        ]);

        $superAdmin->givePermissionTo(Permission::all());
    }
}
