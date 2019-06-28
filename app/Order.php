<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Order extends Pivot
{
    public $table = 'orders';
    protected $fillable = ['restaurant_id', 'client_id', 'order_time', 'order_status', 'menu_id'];
    protected $casts = [
        'order_time' => 'datetime',
    ];
    protected $with = ['orderDb'];
    protected $dates = ['order_time'];

    public function menu(){
        return $this->hasOne(FoodMenu::class, 'order_id');
    }

    public function orderDb(){
        return $this->morphTo();
    }

    public function isBooking(){
        return $this->OrderDb;
    }

    public function restaurant(){
        return $this->belongsTo(Restaurant::class, 'id');
    }

    //
}
