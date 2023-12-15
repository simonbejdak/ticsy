<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Models\Incident\IncidentOnHoldReason;
use App\Models\Incident\IncidentStatus;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use App\Models\Request\RequestOnHoldReason;
use App\Models\Request\RequestStatus;
use Illuminate\Database\Seeder;

class MapSeeder extends Seeder
{
    public function run(): void
    {
        foreach (IncidentCategory::MAP as $key => $value){
            IncidentCategory::factory(['name' => $key])->create();
        }

        foreach (IncidentItem::MAP as $key => $value){
            IncidentItem::factory(['name' => $key])->create();
        }

        foreach (IncidentStatus::MAP as $key => $value){
            IncidentStatus::factory(['name' => $key])->create();
        }

        foreach (IncidentOnHoldReason::MAP as $key => $value){
            IncidentOnHoldReason::factory(['name' => $key])->create();
        }

        foreach (RequestCategory::MAP as $key => $value){
            RequestCategory::factory(['name' => $key])->create();
        }

        foreach (RequestItem::MAP as $key => $value){
            RequestItem::factory(['name' => $key])->create();
        }

        foreach (RequestStatus::MAP as $key => $value){
            RequestStatus::factory(['name' => $key])->create();
        }

        foreach (RequestOnHoldReason::MAP as $key => $value){
            RequestOnHoldReason::factory(['name' => $key])->create();
        }

        foreach (Group::MAP as $key => $value){
            Group::factory(['name' => $key])->create();
        }
    }
}
