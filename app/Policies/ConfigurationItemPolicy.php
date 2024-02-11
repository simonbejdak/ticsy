<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;

class ConfigurationItemPolicy
{
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('view_all_configuration_items');
    }
}
