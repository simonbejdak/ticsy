<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Change;
use App\Models\Incident;
use App\Models\Resolver;
use App\Models\Type;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Request;

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

        Resolver::factory(5)->create();
    }
}
