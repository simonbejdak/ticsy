<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'resolve_ticket',
            'set_number',
            'set_caller',
            'set_created',
            'set_updated',
            'set_type',
            'set_category',
            'set_item',
            'set_description',
            'set_group',
            'set_resolver',
            'set_priority',
            'set_priority_one',
            'set_priority_change_reason',
            'set_status',
            'set_on_hold_reason',
            'add_comments_to_all_tickets',
            'view_all_tickets',
        ];

        foreach ($permissions as $permission){
            Permission::create(['name' => $permission]);
        }

        $roleUser = Role::create(['name' => 'user']);
        $roleUser->givePermissionTo('set_category', 'set_item', 'set_description');

        $roleResolver = Role::create(['name' => 'resolver']);
        $roleResolver->givePermissionTo(
            'resolve_ticket',
            'set_group',
            'set_resolver',
            'set_priority',
            'set_priority_change_reason',
            'set_status',
            'set_on_hold_reason',
            'add_comments_to_all_tickets',
            'view_all_tickets',
        );

        $roleManager = Role::create(['name' => 'manager']);
        $roleManager->givePermissionTo('set_priority_one', 'set_priority_change_reason', 'view_all_tickets');
    }
}
