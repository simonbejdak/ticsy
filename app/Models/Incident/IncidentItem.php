<?php

namespace App\Models\Incident;

use App\Models\Enum;
use App\Models\Request\RequestCategory;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncidentItem extends Enum
{
    use HasFactory;

    const MAP = [
        'issue' => 1,
        'computer_is_too_slow' => 2,
        'application_error' => 3,
        'backup' => 4,
        'failed_node' => 5,
        'failure' => 6,
    ];

    const ISSUE = self::MAP['issue'];
    const COMPUTER_IS_TOO_SLOW = self::MAP['computer_is_too_slow'];
    const APPLICATION_ERROR = self::MAP['application_error'];
    const BACKUP = self::MAP['backup'];
    const FAILED_NODE = self::MAP['failed_node'];
    const FAILURE = self::MAP['failure'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(RequestCategory::class, 'incident_category_incident_item', 'category_id', 'item_id');
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'item_id');
    }
}
