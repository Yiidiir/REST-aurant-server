<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    //
    public function client()
    {
        return $this->hasOne(User::class);
    }
    public function table()
    {
        return $this->hasOne(Table::class);
    }

    public $timestamps = false;
}
