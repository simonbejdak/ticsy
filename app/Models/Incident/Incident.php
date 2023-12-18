<?php

namespace App\Models\Incident;

use App\Models\Group;
use App\Models\Ticket;
use App\Models\Type;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Incident extends Ticket
{
    use LogsActivity;
    use Timestamp;
    use HasFactory;

    protected $casts = [
        'resolved_at' => 'datetime'
    ];
    protected $attributes = [
        'status_id' => self::DEFAULT_STATUS,
        'priority' => self::DEFAULT_PRIORITY,
        'group_id' => self::DEFAULT_GROUP,
    ];

    const DEFAULT_STATUS = IncidentStatus::OPEN;
    const DEFAULT_GROUP = Group::SERVICE_DESK;
    const PRIORITY_TO_SLA_MINUTES = [
        1 => 30,
        2 => 2 * 60,
        3 => 12 * 60,
        4 => 24 * 60,
    ];

    public function defineCategoryClass(): string
    {
        return IncidentCategory::class;
    }
    public function defineItemClass(): string
    {
        return IncidentItem::class;
    }

    public function defineStatusClass(): string
    {
        return IncidentStatus::class;
    }

    public function defineOnHoldReasonClass(): string
    {
        return IncidentOnHoldReason::class;
    }

    public function isArchived(): bool{
        if($this->getOriginal('status_id') == IncidentStatus::RESOLVED){
            $archivalDate = $this->resolved_at->addDays(self::ARCHIVE_AFTER_DAYS);
            if(isset($this->resolved_at) && Carbon::now()->greaterThan($archivalDate)){
                return true;
            }
        }
        return $this->getOriginal('status_id') == IncidentStatus::CANCELLED;
    }

    function isFieldModifiable(string $name): bool
    {
        if($this->isArchived()){
            return false;
        }

        return match($name){
            'category', 'item', 'description' => !$this->exists,
            'status' => auth()->user()->can('update', self::class),
            'onHoldReason' =>
                auth()->user()->can('update', self::class) && $this->isStatus('on_hold'),
            'priority', 'group' =>
                auth()->user()->can('update', self::class) && !$this->isStatus('resolved'),
            'priorityChangeReason' =>
                auth()->user()->can('update', self::class) &&
                $this->priorityChanged() &&
                !$this->isStatus('resolved'),
            'resolver' =>
                auth()->user()->can('update', self::class) &&
                !$this->isStatus('resolved') &&
                ($this->resolver == null || $this->resolver->isGroupMember($this->group)),
            default => false,
        };
    }
}
