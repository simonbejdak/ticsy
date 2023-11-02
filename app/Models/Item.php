<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getNameAttribute($value)
    {
        $value = str_replace('_',' ', $value);
        $value = ucwords($value);

        return $value;
    }
}
