<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\OpeningHours\OpeningHours;

class Restaurant extends Pivot
{
    public $table = 'restaurants';
    protected $fillable = ['id', 'name', 'address', 'class'];

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
        $openingHours = OpeningHours::create(unserialize($this->work_hours));
        return $openingHours->isOpenAt($when);
    }
}
