<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['capacity_min', 'capacity_max', 'class', 'in_restaurant_number', 'available', 'restaurant_id'];
    //
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class);
    }

    public function clients()
    {
        return $this->hasMany(User::class);
    }

    public $timestamps = false;
}
