<?php

namespace App\Helpers;

use App\Models\Category;
use App\Models\Item;

class Config
{
    const CATEGORY_ITEM = [
        [Category::NETWORK, Item::ISSUE],
        [Category::NETWORK, Item::FAILED_NODE],

        [Category::SERVER, Item::ISSUE],
        [Category::SERVER, Item::BACKUP],
        [Category::SERVER, Item::FAILURE],

        [Category::COMPUTER, Item::ISSUE],
        [Category::COMPUTER, Item::COMPUTER_IS_TOO_SLOW],
        [Category::COMPUTER, Item::APPLICATION_ERROR],
        [Category::COMPUTER, Item::FAILURE],

        [Category::APPLICATION, Item::ISSUE],
        [Category::APPLICATION, Item::APPLICATION_ERROR],

        [Category::EMAIL, Item::ISSUE],
        [Category::EMAIL, Item::BACKUP],
    ];
}
