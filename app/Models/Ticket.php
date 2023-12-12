<?php

namespace App\Models;

use App\Helpers\Fieldable;
use App\Helpers\Slable;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ticket extends Model implements Slable, Fieldable
{
    use Timestamp;
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];
    protected $attributes = [
        'status_id' => self::DEFAULT_STATUS,
        'priority' => self::DEFAULT_PRIORITY,
        'group_id' => self::DEFAULT_GROUP,
    ];

    protected $casts = [
        'resolved_at' => 'datetime'
    ];

    protected $appends = ['sla'];

    const ARCHIVE_AFTER_DAYS = 4;
    const PRIORITIES = [1, 2, 3, 4];
    const DEFAULT_PRIORITY = 4;
    const DEFAULT_STATUS = Status::OPEN;
    const DEFAULT_GROUP = Group::SERVICE_DESK;
    const PRIORITY_SLA = [
        1 => 30,
        2 => 2 * 60,
        3 => 12 * 60,
        4 => 24 * 60,
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function caller()
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function onHoldReason()
    {
        return $this->belongsTo(OnHoldReason::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function slas()
    {
        return $this->morphMany(Sla::class, 'slable');
    }

    public function isStatus(...$statuses): bool{
        foreach ($statuses as $status){
            if($this->status_id == Status::MAP[$status]){
                return true;
            }
        }
        return false;
    }

    public function isArchived(): bool{
        if($this->getOriginal('status_id') == Status::RESOLVED){
            $archivalDate = $this->resolved_at->addDays(Ticket::ARCHIVE_AFTER_DAYS);
            if(isset($this->resolved_at) && Carbon::now()->greaterThan($archivalDate)){
                return true;
            }
        }
        return $this->getOriginal('status_id') == Status::CANCELLED;
    }

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'category.name',
                'item.name',
                'description',
                'status.name',
                'onHoldReason.name',
                'priority', 'group.name',
                'resolver.name',
            ])
            ->logOnlyDirty();
    }

    public function calculateSlaMinutes(): int
    {
        return self::PRIORITY_SLA[$this->priority];
    }

    public function getSlaAttribute(): Sla
    {
        return $this->slas->last();
    }

    public function isFieldModifiable(string $name): bool
    {
        if($this->isArchived()){
            return false;
        }

        return match($name){
            'category' =>
                auth()->user()->can('setCategory', Ticket::class) && !$this->exists,
            'item' =>
                auth()->user()->can('setItem', Ticket::class) && !$this->exists,
            'description' =>
                auth()->user()->can('setDescription', Ticket::class) && !$this->exists,
            'status' =>
                auth()->user()->can('setStatus', Ticket::class),
            'onHoldReason' =>
                auth()->user()->can('setOnHoldReason', Ticket::class) && $this->isStatus('on_hold'),
            'priority' =>
                auth()->user()->can('setPriority', Ticket::class) && !$this->isStatus('resolved'),
            'priorityChangeReason' =>
                auth()->user()->can('setPriorityChangeReason', Ticket::class) &&
                $this->isDirty('priority') &&
                !$this->isStatus('resolved'),
            'group' =>
                auth()->user()->can('setGroup', Ticket::class) && !$this->isStatus('resolved'),
            'resolver' =>
                auth()->user()->can('setResolver', Ticket::class) &&
                !$this->isStatus('resolved') &&
                ($this->resolver == null ? true : $this->resolver->isGroupMember($this->group)),
            default => false,
        };
    }
}
