<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Type;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ])
            ->resolver()
            ->canChangePriority()
            ->create();

        $resolver = User::factory([
            'name' => 'Resolver',
            'email' => 'resolver@gmail.com',
        ])
            ->resolver()
            ->create();

        $user = User::factory([
            'name' => 'User',
            'email' => 'user@gmail.com',
        ])
            ->resolver()
            ->create();

        foreach (Ticket::TYPES as $key => $value){
            Type::factory(['name' => $key])->create();
        }

        foreach (Ticket::CATEGORIES as $key => $value){
            Category::factory(['name' => $key])->create();
        }

        foreach (range(1, 30) as $iteration){
            Ticket::factory()->create([
                'user_id' => $admin,
                'category_id' => rand(1, count(Ticket::CATEGORIES)),
                'type_id' => rand(1, count(Ticket::TYPES)),
                'resolver_id' => $resolver,
            ]);
        }

        $tickets = Ticket::all();

        foreach ($tickets as $ticket){
            Comment::factory(5)->create([
                'ticket_id' => $ticket,
                'user_id' => $user,
            ]);
        }

        User::factory(5)->resolver()->create();
    }
}
