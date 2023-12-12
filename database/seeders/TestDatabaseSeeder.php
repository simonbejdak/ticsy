<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MapSeeder::class,
            TestCategoryItemSeeder::class,
            TestRequestCategoryRequestItemSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
