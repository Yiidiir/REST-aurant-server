<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    protected $fillable = ['address'];
    //
    public function order()
    {
        return $this->morphOne('App\Order', 'orderDb');
    }
}
