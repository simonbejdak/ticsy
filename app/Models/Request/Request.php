<?php

namespace App\Models\Request;

use App\Models\Group;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Models\Incident\IncidentOnHoldReason;
use App\Models\Incident\IncidentStatus;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;

class Request extends Ticket
{
    use HasFactory;

    const DEFAULT_STATUS = RequestStatus::OPEN;
    const DEFAULT_GROUP = Group::SERVICE_DESK;
    const PRIORITY_TO_SLA_MINUTES = [
        1 => 30,
        2 => 60,
        3 => 12 * 60,
        4 => 24 * 60
    ];

    protected $attributes = [
        'status_id' => self::DEFAULT_STATUS,
        'priority' => self::DEFAULT_PRIORITY,
        'group_id' => self::DEFAULT_GROUP,
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function defineCategoryClass(): string
    {
        return RequestCategory::class;
    }
    public function defineItemClass(): string
    {
        return RequestItem::class;
    }

    public function defineStatusClass(): string
    {
        return RequestStatus::class;
    }

    public function defineOnHoldReasonClass(): string
    {
        return RequestOnHoldReason::class;
    }

    public function isArchived(): bool{
        if($this->getOriginal('status_id') == RequestStatus::CLOSED){
            $archivalDate = $this->closed_at->addDays(self::ARCHIVE_AFTER_DAYS);
            if(isset($this->closed_at) && Carbon::now()->greaterThan($archivalDate)){
                return true;
            }
        }
        return $this->getOriginal('status_id') == RequestStatus::CANCELLED;
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
                auth()->user()->can('update', self::class) && !$this->isStatus('closed'),
            'priorityChangeReason' =>
                auth()->user()->can('update', self::class) &&
                $this->priorityChanged() &&
                !$this->isStatus('closed'),
            'resolver' =>
                auth()->user()->can('update', self::class) &&
                !$this->isStatus('closed') &&
                ($this->resolver == null || $this->resolver->isGroupMember($this->group)),
            default => false,
        };
    }
}
