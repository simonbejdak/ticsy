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
        Permission::create(['name' => 'set_priority']);
        Permission::create(['name' => 'add_comments_to_all_tickets']);
        Permission::create(['name' => 'view_resolver_panel']);

        Role::create(['name' => 'resolver'])
            ->givePermissionTo(
                'view_all_tickets',
                'update_all_tickets',
                'add_comments_to_all_tickets',
                'view_resolver_panel',
            );

        Role::create(['name' => 'manager'])
            ->givePermissionTo(
            'view_all_tickets',
            'update_all_tickets',
            'add_comments_to_all_tickets',
            'set_priority',
            'view_resolver_panel',
        );
    }
}
