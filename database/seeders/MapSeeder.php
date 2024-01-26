<?php

namespace Database\Seeders;

use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
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

        foreach (RequestCategory::MAP as $key => $value){
            RequestCategory::factory(['name' => $key])->create();
        }

        foreach (RequestItem::MAP as $key => $value){
            RequestItem::factory(['name' => $key])->create();
        }
    }
}
