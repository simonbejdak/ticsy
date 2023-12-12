<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Group;
use App\Models\Item;
use App\Models\RequestCategory;
use App\Models\RequestOnHoldReason;
use App\Models\RequestStatus;
use App\Models\Status;
use App\Models\OnHoldReason;
use App\Models\TicketConfig;
use App\Models\Type;
use Illuminate\Database\Seeder;
use PHPUnit\Framework\Attributes\Ticket;

class MapSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Type::MAP as $key => $value){
            Type::factory(['name' => $key])->create();
        }

        foreach (Category::MAP as $key => $value){
            Category::factory(['name' => $key])->create();
        }

        foreach (Item::MAP as $key => $value){
            Item::factory(['name' => $key])->create();
        }

        foreach (Status::MAP as $key => $value){
            Status::factory(['name' => $key])->create();
        }

        foreach (OnHoldReason::MAP as $key => $value){
            OnHoldReason::factory(['name' => $key])->create();
        }

        foreach (Group::MAP as $key => $value){
            Group::factory(['name' => $key])->create();
        }

        foreach (RequestCategory::MAP as $key => $value){
            RequestCategory::factory(['name' => $key])->create();
        }

        foreach (RequestStatus::MAP as $key => $value){
            RequestStatus::factory(['name' => $key])->create();
        }

        foreach (RequestOnHoldReason::MAP as $key => $value){
            RequestOnHoldReason::factory(['name' => $key])->create();
        }
    }
}
