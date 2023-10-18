<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Group;
use App\Models\Status;
use App\Models\TicketConfiguration;
use App\Models\Type;
use Illuminate\Database\Seeder;

class MapSeeder extends Seeder
{
    public function run(): void
    {
        foreach (TicketConfiguration::TYPES as $key => $value){
            Type::factory(['name' => $key])->create();
        }

        foreach (TicketConfiguration::CATEGORIES as $key => $value){
            Category::factory(['name' => $key])->create();
        }

        foreach (TicketConfiguration::STATUSES as $key => $value){
            Status::factory(['name' => $key])->create();
        }

        foreach (Group::GROUPS as $key => $value){
            Group::factory(['name' => $key])->create();
        }
    }
}
