<?php

namespace App\Enums;

enum OnHoldReason: string
{
    case CALLER_RESPONSE = 'Caller Response';
    case MONITORING = 'Monitoring';
    case WAITING_FOR_VENDOR = 'Waiting for Vendor';
    case WAITING_FOR_CHANGE = 'Waiting for Change';
}
