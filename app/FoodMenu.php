<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FoodMenu extends Pivot
{
    //
    public $timestamps = false;

    public function foods(){
        return $this->hasMany(Food::class, 'id', 'food_id');
    }
    public function order(){
        return $this->belongsTo(Order::class, 'id', 'order_id');
    }
}
