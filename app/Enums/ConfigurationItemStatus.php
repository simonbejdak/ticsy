<?php

namespace App\Enums;

enum ConfigurationItemStatus: string
{
    case INSTALLED = 'Installed';
    case INSTALLED_INACTIVE = 'Installed - Inactive';
    case IN_STOCK = 'In Stock';
    case RETIRED = 'Retired';
    case STANDALONE = 'Standalone';
}
