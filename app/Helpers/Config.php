<?php

namespace App\Helpers;

use App\Models\Category;
use App\Models\Item;
use App\Models\RequestCategory;
use App\Models\RequestItem;

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

    const REQUEST_CATEGORY_REQUEST_ITEM = [
        [RequestCategory::NETWORK, RequestItem::ISSUE],
        [RequestCategory::NETWORK, RequestItem::FAILED_NODE],

        [RequestCategory::SERVER, RequestItem::ISSUE],
        [RequestCategory::SERVER, RequestItem::BACKUP],
        [RequestCategory::SERVER, RequestItem::FAILURE],

        [RequestCategory::COMPUTER, RequestItem::ISSUE],
        [RequestCategory::COMPUTER, RequestItem::COMPUTER_IS_TOO_SLOW],
        [RequestCategory::COMPUTER, RequestItem::APPLICATION_ERROR],
        [RequestCategory::COMPUTER, RequestItem::FAILURE],

        [RequestCategory::APPLICATION, RequestItem::ISSUE],
        [RequestCategory::APPLICATION, RequestItem::APPLICATION_ERROR],

        [RequestCategory::EMAIL, RequestItem::ISSUE],
        [RequestCategory::EMAIL, RequestItem::BACKUP],
    ];
}
