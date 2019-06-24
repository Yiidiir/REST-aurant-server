<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBooking extends Model
{
    //
    public function order()
    {
        return $this->morphOne('App\Order', 'orderDb');
    }
}
