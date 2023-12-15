<?php

namespace Database\Seeders;

use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use Illuminate\Database\Seeder;

class TestIncidentCategoryIncidentItemSeeder extends Seeder
{
    public function run(): void
    {
        foreach (IncidentCategory::all() as $category){
            foreach (IncidentItem::all() as $item){
                $category->items()->attach($item);
            }
        }
    }
}
