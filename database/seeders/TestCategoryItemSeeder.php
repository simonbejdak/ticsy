<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class TestCategoryItemSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Category::all() as $category){
            foreach (Item::all() as $item){
                $category->items()->attach($item);
            }
        }
    }
}
