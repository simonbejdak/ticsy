<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Group;
use App\Models\TicketConfiguration;
use App\Models\Type;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MapSeeder::class,
            PermissionSeeder::class,
        ]);

        $user = User::factory([
            'name' => 'User',
            'email' => 'user@gmail.com',
        ])->create();

        $resolver = User::factory([
            'name' => 'Resolver',
            'email' => 'resolver@gmail.com',
        ])->create()->assignRole('resolver');

        foreach (range(1, 30) as $iteration){
            Ticket::factory()->create([
                'user_id' => $user,
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

        foreach (range(1, 5) as $iteration){
            User::factory()->create()->assignRole('resolver');
        }

    }
}
