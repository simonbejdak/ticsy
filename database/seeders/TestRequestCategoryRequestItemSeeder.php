<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\RequestCategory;
use App\Models\RequestItem;
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
