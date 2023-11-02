<?php

namespace App\Models;

class TicketConfig
{
    const TYPES = [
        'incident' => 1,
        'request' => 2,
        'change' => 3,
    ];
    const DEFAULT_TYPE = [
        'id' => self::TYPES['incident'],
        'name' => 'incident',
    ];
    const CATEGORIES = [
        'network' => 1,
        'server' => 2,
        'computer' => 3,
        'application' => 4,
        'email' => 5,
    ];

    const ITEMS = [
        'issue' => 1,
        'computer_is_too_slow' => 2,
        'application_error' => 3,
        'backup' => 4,
        'failed_node' => 5,
        'failure' => 6,
    ];

    const CATEGORY_ITEM = [
        [self::CATEGORIES['network'], self::ITEMS['issue']],
        [self::CATEGORIES['network'], self::ITEMS['failed_node']],

        [self::CATEGORIES['server'], self::ITEMS['issue']],
        [self::CATEGORIES['server'], self::ITEMS['backup']],
        [self::CATEGORIES['server'], self::ITEMS['failure']],

        [self::CATEGORIES['computer'], self::ITEMS['issue']],
        [self::CATEGORIES['computer'], self::ITEMS['computer_is_too_slow']],
        [self::CATEGORIES['computer'], self::ITEMS['application_error']],
        [self::CATEGORIES['computer'], self::ITEMS['failure']],

        [self::CATEGORIES['application'], self::ITEMS['issue']],
        [self::CATEGORIES['application'], self::ITEMS['application_error']],

        [self::CATEGORIES['email'], self::ITEMS['issue']],
        [self::CATEGORIES['email'], self::ITEMS['backup']],
    ];

    const STATUSES = [
        'open' => 1,
        'in_progress' => 2,
        'on_hold' => 3,
        'monitoring' => 4,
        'resolved' => 5,
        'cancelled' => 6,
    ];
    const DEFAULT_STATUS = self::STATUSES['open'];
    const PRIORITIES = [1, 2, 3, 4];
    const DEFAULT_PRIORITY = 4;
    const DEFAULT_PAGINATION = 10;
    const MIN_DESCRIPTION_CHARS = 8;
    const MAX_DESCRIPTION_CHARS = 255;
    const ARCHIVE_AFTER_DAYS = 3;
}
