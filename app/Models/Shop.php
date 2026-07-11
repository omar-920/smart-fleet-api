<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Shop extends Model
{
    use Notifiable;
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
