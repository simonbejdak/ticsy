<?php

namespace Database\Seeders;

use App\Models\Category;
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
    }
}
