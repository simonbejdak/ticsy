<?php

namespace App\Models;

use App\Helpers\Config;
use App\Observers\TicketObserver;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    use Timestamp;
    use HasFactory;

    protected $guarded = [];
    protected $attributes = [
        'status_id' => TicketConfiguration::DEFAULT_STATUS,
        'priority' => TicketConfiguration::DEFAULT_PRIORITY,
        'group_id' => Group::DEFAULT,
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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function isResolved(): bool
    {
        return $this->status_id == TicketConfiguration::STATUSES['resolved'];
    }

    public function isArchived(): bool{
        if($this->status->id == TicketConfiguration::STATUSES['resolved']){

            $expireDate = Carbon::now()->subDays(TicketConfiguration::ARCHIVE_AFTER_DAYS);

            if(isset($this->resolved_at) && $this->resolved_at->lessThan($expireDate)){
                return true;
            }
        };

        if($this->status->id === TicketConfiguration::STATUSES['cancelled']){
            return true;
        };

        return false;
    }

    public function isStatus(...$statuses): bool{
        foreach ($statuses as $status){
            if($this->status_id == TicketConfiguration::STATUSES[$status]){
                return true;
            }
        }
        return false;
    }
}
