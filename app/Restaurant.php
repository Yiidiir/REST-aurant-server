<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Restaurant extends Pivot
{
    public $table = 'restaurants';
    protected $fillable = ['id', 'name', 'address'];
    //
    public function tables(){
        return $this->hasMany(Table::class, 'restaurant_id');
    }

    public function owner(){
        return $this->belongsTo(User::class);
    }

    public function foods(){
        return $this->hasMany(Food::class, 'restaurant_id');
    }
}
