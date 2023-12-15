<?php

namespace Database\Seeders;

use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use Illuminate\Database\Seeder;

class TestRequestCategoryRequestItemSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RequestCategory::all() as $category){
            foreach (RequestItem::all() as $item){
                $category->items()->attach($item);
            }
        }
    }
}
