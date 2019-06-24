<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBooking extends Model
{
    protected $fillable = ['table_id'];
    //
    public function order()
    {
        return $this->morphOne('App\Order', 'orderDb');
    }
}
