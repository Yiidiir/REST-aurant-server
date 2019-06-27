<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\OpeningHours\OpeningHours;

class Restaurant extends Pivot
{
    public $table = 'restaurants';
    protected $fillable = ['id', 'name', 'address', 'class', 'work_hours'];

    //
    public function tables()
    {
        return $this->hasMany(Table::class, 'restaurant_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function foods()
    {
        return $this->hasMany(Food::class, 'restaurant_id');
    }

    public function isOpenAt($when)
    {
        $openingHours = OpeningHours::create(unserialize($this->work_hours), false);
//        return json_decode(unserialize($this->work_hours), true);
        return $openingHours->isOpenAt($when);
    }
}
