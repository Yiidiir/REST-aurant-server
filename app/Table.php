<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    //
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function clients()
    {
        return $this->hasMany(User::class);
    }

    public $timestamps = false;
}
