<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
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
        $me = User::factory([
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

        $tickets = Ticket::factory(30)->existing()->create();

        foreach ($tickets as $ticket){
            Comment::factory(5, ['ticket_id' => $ticket])->create();
        }

        User::factory(5)->resolver()->create();
    }
}
