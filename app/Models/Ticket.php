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

    const ARCHIVE_AFTER_DAYS = 3;
    const PRIORITIES = [1, 2, 3, 4];
    const PRIORITY_ONE = 1;
    const DEFAULT_PRIORITY = 4;
    const MIN_DESCRIPTION_CHARS = 8;
    const MAX_DESCRIPTION_CHARS = 255;
    const PRIORITY_SLA = [
        1 => 30,
        2 => 2 * 60,
        3 => 12 * 60,
        4 => 24 * 60,
    ];

    protected $guarded = [];
    protected $attributes = [
        'status_id' => Status::DEFAULT,
        'priority' => self::DEFAULT_PRIORITY,
        'group_id' => Group::DEFAULT,
    ];

    public array $loggableAttributes = [
        'category.name',
        'item.name',
        'description',
        'status.name',
        'onHoldReason.name',
        'priority', 'group.name',
        'resolver.name',
    ];

    protected $casts = [
        'resolved_at' => 'datetime'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class);
    }

    public function assign(User $resolver)
    {
        $this->resolver = $resolver;
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

    public function isArchived(): bool{
        if($this->getOriginal('status_id') == Status::RESOLVED){
            $archivalDate = $this->resolved_at->addDays(Ticket::ARCHIVE_AFTER_DAYS);
            if(isset($this->resolved_at) && Carbon::now()->greaterThan($archivalDate)){
                return true;
            }
        }
        return $this->getOriginal('status_id') == Status::CANCELLED;
    }

    public function isStatus(...$statuses): bool{
        foreach ($statuses as $status){
            if($this->status_id == Status::MAP[$status]){
                return true;
            }
        }
        return false;
    }

    public function isStatusOpen(): bool
    {
        return $this->isStatus('open');
    }

    public function isStatusProgress(): bool
    {
        return $this->isStatus('in_progress');
    }

    public function isStatusOnHold(): bool
    {
        return $this->isStatus('on_hold');
    }

    public function isStatusResolved(): bool
    {
        return $this->isStatus('resolved');
    }

    public function isStatusCancelled(): bool
    {
        return $this->isStatus('cancelled');
    }


    public function isNotStatus($status): bool{
        return !$this->isStatus($status);
    }

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->loggableAttributes)
            ->logOnlyDirty();
    }

    public function calculateSlaMinutes(): int
    {
        return self::PRIORITY_SLA[$this->priority];
    }

    public function sla(): Sla
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
                auth()->user()->can('setOnHoldReason', Ticket::class) && $this->isStatusOnHold(),
            'priority' =>
                auth()->user()->can('setPriority', Ticket::class) && !$this->isStatusResolved(),
            'priorityChangeReason' =>
                auth()->user()->can('setPriorityChangeReason', Ticket::class) &&
                $this->isDirty('priority') &&
                !$this->isStatusResolved(),
            'group' =>
                auth()->user()->can('setGroup', Ticket::class) && !$this->isStatusResolved(),
            'resolver' =>
                auth()->user()->can('setResolver', Ticket::class) &&
                !$this->isStatusResolved() &&
                ($this->resolver == null ? true : $this->resolver->isGroupMember($this->group)),
            default => false,
        };
    }
}
