<?php

namespace Database\Seeders;

use App\Helpers\Config;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Item;
use App\Models\TicketConfig;
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
            CategoryItemSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);;

        $user = User::factory([
            'name' => 'User',
            'email' => 'user@gmail.com',
        ])->create();

        $resolver = User::factory([
            'name' => 'Resolver',
            'email' => 'resolver@gmail.com',
        ])->resolver()->create();

        $manager = User::factory([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
        ])->manager()->create();

        Ticket::factory(30)->create([
            'caller_id' => $user,
        ]);

        User::factory(5)->resolver()->create();

        foreach (User::role('resolver')->get() as $resolver){
            $resolver->groups()->attach(Group::find(rand(1, Group::count())));
        }
    }
}
