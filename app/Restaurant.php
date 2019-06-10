<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Restaurant extends Pivot
{
    public $table = 'restaurants';
    protected $fillable = ['id',];
    //
}
