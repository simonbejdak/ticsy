<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        Group::factory(['name' => 'SERVICE-DESK'])->create();
        Group::factory(['name' => 'LOCAL-6445-NEW-YORK'])->create();
        Group::factory(['name' => 'GIT-ENG'])->create();
        Group::factory(['name' => 'LOCAL-6380-SKOPJE'])->create();
        Group::factory(['name' => 'ANTIVIRUS-TEAM'])->create();
    }
}
