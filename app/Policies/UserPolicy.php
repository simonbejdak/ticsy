<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;

class UserPolicy
{
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('view_all_users');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('update_all_users');
    }
}
