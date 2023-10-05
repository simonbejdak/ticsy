<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Type;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory([
            'name' => 'Šimon Bejdák',
            'email' => 'bejdakxd@gmail.com',
        ])
            ->resolver()
            ->canChangePriority()
            ->create();

        foreach (Ticket::TYPES as $key => $value){
            Type::factory(['name' => $key])->create();
        }

        foreach (Ticket::CATEGORIES as $key => $value){
            Category::factory(['name' => $key])->create();
        }

        Ticket::factory(30)->existing()->create();

        User::factory(5)->resolver()->create();
    }
}
