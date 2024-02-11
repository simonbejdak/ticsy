<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\ConfigurationItem;
use App\Models\Group;
use App\Models\Incident;
use App\Models\Request;
use App\Models\TicketConfig;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MapSeeder::class,
            IncidentCategoryIncidentItemSeeder::class,
            RequestCategoryRequestItemSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            GroupSeeder::class,
        ]);

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

        ConfigurationItem::factory(100)->create();
        Incident::factory(100)->create();
        Request::factory(100)->create();

        $resolvers = User::factory(25)->resolver()->create();

        foreach ($resolvers as $resolver){
            $resolver->groups()->attach(Group::findOrFail(rand(1, count(Group::all()))));
        }
    }
}
