<?php

namespace Database\Seeders;

use App\Models\TicketConfig;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory([
            'name' => 'System',
            'email' => 'system@gmail.com',
        ])->create();
    }
}
