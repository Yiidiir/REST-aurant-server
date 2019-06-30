<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class User
 * @package App\Http\Resources
 */
class User extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'email' => $this->resource->email,
            'phone_number' => $this->resource->phone_number,
            'role' => $this->resource->role,
            'api_token' => $this->resource->api_token,
            'join_date' => $this->created_at->format('l, d F Y'),
        ];
    }
}
