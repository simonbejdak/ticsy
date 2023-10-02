<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    const TYPES = [
        'incident' => 1,
        'request' => 2,
        'change' => 3,
    ];

    const CATEGORIES = [
        'network' => 1,
        'server' => 2,
        'computer' => 3,
        'application' => 4,
        'email' => 5,
    ];

    const PRIORITIES = [1, 2, 3, 4];
    const DEFAULT_PRIORITY = 4;

    const DEFAULT_PAGINATION = 10;

    use HasFactory;

    protected $guarded = [];
    protected $attributes = [
        'priority' => self::DEFAULT_PRIORITY,
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
        return $this->belongsTo(Resolver::class);
    }

    public function assign(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function setPriority(int $number): bool
    {
        if(Auth::user()->can('setPriority', $this)){
            $this->priority = $number;
            return true;
        }

        abort(403);
    }
}
