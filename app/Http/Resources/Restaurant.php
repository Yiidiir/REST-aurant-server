<?php

namespace App\Http\Resources;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Restaurant extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->name,
            'address' => $this->address,
            'class' => $this->class,
            'updated_at' => Carbon::parse($this->updated_at)->diffForHumans(),
            'tables' => $this->tables()->get(),
            'foods' => $this->foods()->get()
        ];
    }
}
