<?php

namespace App\Helpers;

use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;

class Config
{
    const INCIDENT_CATEGORY_TO_INCIDENT_ITEM = [
        [IncidentCategory::NETWORK, IncidentItem::ISSUE],
        [IncidentCategory::NETWORK, IncidentItem::FAILED_NODE],

        [IncidentCategory::SERVER, IncidentItem::ISSUE],
        [IncidentCategory::SERVER, IncidentItem::BACKUP],
        [IncidentCategory::SERVER, IncidentItem::FAILURE],

        [IncidentCategory::COMPUTER, IncidentItem::ISSUE],
        [IncidentCategory::COMPUTER, IncidentItem::COMPUTER_IS_TOO_SLOW],
        [IncidentCategory::COMPUTER, IncidentItem::APPLICATION_ERROR],
        [IncidentCategory::COMPUTER, IncidentItem::FAILURE],

        [IncidentCategory::APPLICATION, IncidentItem::ISSUE],
        [IncidentCategory::APPLICATION, IncidentItem::APPLICATION_ERROR],

        [IncidentCategory::EMAIL, IncidentItem::ISSUE],
        [IncidentCategory::EMAIL, IncidentItem::BACKUP],
    ];

    const REQUEST_CATEGORY_TO_REQUEST_ITEM = [
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
