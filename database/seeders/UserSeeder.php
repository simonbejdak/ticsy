<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Group;
use App\Models\Item;
use App\Models\Status;
use App\Models\OnHoldReason;
use App\Models\TicketConfig;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;
use PHPUnit\Framework\Attributes\Ticket;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory([
            'name' => 'System',
            'email' => 'system@gmail.com',
        ])->create();
    }
}
