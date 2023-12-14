<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::create(['name' => 'view_all_tickets']);
        Permission::create(['name' => 'update_all_tickets']);
        Permission::create(['name' => 'set_priority_one']);
        Permission::create(['name' => 'add_comments_to_all_tickets']);

        $roleResolver = Role::create(['name' => 'resolver']);
        $roleResolver->givePermissionTo(
            'view_all_tickets',
            'update_all_tickets',
            'add_comments_to_all_tickets',
        );

        $roleManager = Role::create(['name' => 'manager']);
        $roleManager->givePermissionTo(
            'view_all_tickets',
            'update_all_tickets',
            'add_comments_to_all_tickets',
            'set_priority_one',
        );
    }
}
