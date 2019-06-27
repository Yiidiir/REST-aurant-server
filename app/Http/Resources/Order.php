<?php

namespace App\Http\Resources;

use App\FoodMenu;
use App\User;
use App\Restaurant;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Food as FoodResource;

/**
 * @property mixed menu_id
 * @property mixed order_status
 * @property mixed order_time
 * @property mixed client_id
 * @property mixed restaurant_id
 * @property mixed id
 */
class Order extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'restaurant_id' => $this->restaurant_id,
            'restaurant_name' => Restaurant::find($this->restaurant_id)->name,
            'client_id' => $this->client_id,
            'client_fullname' => User::find($this->client_id)->first_name . ' ' . User::find($this->client_id)->last_name,
            'order_time' => $this->order_time->format('l, d F Y'),
            'order_status' => $this->statusConvert($this->order_status),
            'order_type' => $this->orderTypeConvert($this->orderDb_type),
            'menu_id' => $this->menu_id,
            'foods' => FoodResource::collection($this->menu()->get()),
            'price' => $this->calculatePrice($this->menu()->get())
        ];
    }

    private function statusConvert($status)
    {
        if ($status == 1) {
            return 'Processing';
        } elseif ($status == 2) {
            return 'Completed';
        } else {
            return 'Cancelled';
        }
    }

    private function calculatePrice($foods)
    {
        $price = 0;
        foreach ($foods as $food) {
            $price = $price + \App\Food::find($food->food_id)->price;
        }
        return $price;
    }

    private function orderTypeConvert($type)
    {
        if ($type == 'App\OrderBooking') {
            return 'Table Booking';
        } else {
            return 'Food Delivery';
        }
    }
}
