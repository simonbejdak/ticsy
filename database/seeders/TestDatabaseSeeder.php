<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Resolver;
use App\Models\Ticket;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Ticket::TYPES as $key => $value){
            Type::factory(['name' => $key])->create();
        }

        foreach (Ticket::CATEGORIES as $key => $value){
            Category::factory(['name' => $key])->create();
        }
    }
}
