<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
