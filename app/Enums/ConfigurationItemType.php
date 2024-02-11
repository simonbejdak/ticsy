<?php

namespace App\Enums;

enum ConfigurationItemType: string
{
    case LAB_TEST = 'LAB Test';
    case PRIMARY = 'Primary';
    case SECONDARY = 'Secondary';
    case SHOP_FLOOR = 'Shop Floor';
}
