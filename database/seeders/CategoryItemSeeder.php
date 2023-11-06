<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\TicketConfig;
use Illuminate\Database\Seeder;

class CategoryItemSeeder extends Seeder
{
    public function run(): void
    {
        foreach (TicketConfig::CATEGORY_ITEM as $value){
            $category = Category::findOrFail($value[0]);
            $item = Item::findOrFail($value[1]);
            $category->items()->attach($item);
        }
    }
}
