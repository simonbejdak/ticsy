<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $attributes = [
        'status_id' => TicketConfiguration::DEFAULT_STATUS,
        'priority' => TicketConfiguration::DEFAULT_PRIORITY,
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

    public function archived(){
        if($this->status->id === TicketConfiguration::STATUSES['resolved']){
            return true;
        };
        if($this->status->id === TicketConfiguration::STATUSES['cancelled']){
            return true;
        };
        return false;
    }
}
