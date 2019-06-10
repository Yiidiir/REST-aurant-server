<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Order extends Pivot
{
    public $table = 'orders';
    //
}
