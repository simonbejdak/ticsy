<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ticket extends Model
{
    use Timestamp;
    use HasFactory;
    use LogsActivity;

    const ARCHIVE_AFTER_DAYS = 3;
    const PRIORITIES = [1, 2, 3, 4];
    const DEFAULT_PRIORITY = 4;
    const MIN_DESCRIPTION_CHARS = 8;
    const MAX_DESCRIPTION_CHARS = 255;

    protected $guarded = [];
    protected $attributes = [
        'status_id' => Status::DEFAULT,
        'priority' => self::DEFAULT_PRIORITY,
        'group_id' => Group::DEFAULT,
    ];

    public array $loggableAttributes = [
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

    public function isResolved(): bool
    {
        return $this->getOriginal('status_id') == Status::RESOLVED;
    }

    public function isCancelled(): bool
    {
        return $this->getOriginal('status_id') == Status::CANCELLED;
    }

    public function isArchived(): bool{
        if($this->isResolved()){

            $expireDate = Carbon::now()->subDays(Ticket::ARCHIVE_AFTER_DAYS);

            if(isset($this->resolved_at) && $this->resolved_at->lessThan($expireDate)){
                return true;
            }
        };

        return $this->isCancelled();
    }

    public function isStatus(...$statuses): bool{
        foreach ($statuses as $status){
            if($this->status_id == Status::MAP[$status]){
                return true;
            }
        }
        return false;
    }

    public function addComment($body)
    {
        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->event('comment')
            ->log($body);
    }

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->loggableAttributes)
            ->logOnlyDirty();
    }
}
