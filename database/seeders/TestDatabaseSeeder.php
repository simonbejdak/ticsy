<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Resolver;
use App\Models\Ticket;
use App\Models\TicketConfig;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MapSeeder::class,
            TestCategoryItemSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
