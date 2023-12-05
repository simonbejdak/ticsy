<?php

namespace App\Models;

use App\Helpers\Slable;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ticket extends Model implements Slable
{
    use Timestamp;
    use HasFactory;
    use LogsActivity;

    const ARCHIVE_AFTER_DAYS = 3;
    const PRIORITIES = [1, 2, 3, 4];
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

    public function comments()
    {
        return $this->hasMany(Comment::class);
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

    public function isResolved(): bool
    {
        return $this->isStatus('resolved');
    }

    public function isCancelled(): bool
    {
        return $this->isStatus('cancelled');
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
}
