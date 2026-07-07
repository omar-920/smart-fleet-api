<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $guarded = [];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
