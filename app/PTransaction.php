<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PTransaction extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'id',
        'payer_name',
        'payer_ip',
        'payment_timestamp',
        'card_brand',
        'card_country',
        'card_zip',
        'card_exp',
        'card_id',
        'card_last4',
        'order_id'];
}
