<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public $timestamps = false;
}
