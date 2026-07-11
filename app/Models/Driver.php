<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Driver extends Model
{
    use Notifiable;
        protected $guarded = [];


    public function routeNotificationForMail($notification)
    {
        // بنقوله روح للعلاقة بتاعت اليوزر وهات الإيميل بتاعه
        return $this->user->email;
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
