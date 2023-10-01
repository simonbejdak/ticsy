<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Resolver;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ResolverFactory extends UserFactory
{
    protected $model = Resolver::class;

    public function definition(): array
    {
        return array_merge(parent::definition(), [
            'group_id' => Group::factory()->create(),
        ]);
    }
}
