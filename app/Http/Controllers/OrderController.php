<?php

namespace App\Http\Controllers;

use App\FoodMenu;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\Food as FoodResource;
use App\Order;
use App\OrderBooking;
use App\OrderDelivery;
use App\Restaurant;
use App\Table;
use Carbon\Carbon;
use function GuzzleHttp\Promise\all;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('api')->user();
        if ($user->isClient()) {
            return OrderResource::collection(Order::where('client_id', $user->id)->orderBy('id', 'DESC')->get());
        } elseif ($user->isAdmin()) {
            return OrderResource::collection(Order::all()->get());
        } elseif ($user->isOwner()) {
            $owner_restaurants = Restaurant::where('owner_id', $user->id)->pluck('id');
            return OrderResource::collection(Order::whereIn('restaurant_id', $owner_restaurants)->orderBy('id', 'DESC')->get());
        }
        throw new AuthenticationException;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        if ($user->isClient()) {
            $foodIds = json_decode($request->input('foods'), true);
            $order = Order::create([
                'restaurant_id' => $request->input('restaurant_id'),
                'client_id' => $user->id,
                'order_time' => Carbon::createFromFormat('Y-m-d H:i',$request->input('order_date') . ' ' . $request->input('order_time'), 'Africa/Algiers')->addHours(1)->timestamp,
                'order_status' => '1',
                'menu_id' => 0,
                'orderDb_type' => $request->input('order_type'),
            ]);
            foreach ($foodIds as $foodId) {
                $menu = FoodMenu::create([
                    'food_id' => $foodId,
                    'order_id' => $order->id
                ]);
            }
            $order->update([
                'menu_id' => $menu->id
            ]);
            if ($request->input('order_type') == 1) {
                $delivery = OrderDelivery::create(['address' => $request->input('delivery_address')]);
                $delivery->order()->save($order);
            } else {
                $table_id = $request->input('table_id');
                $booking = OrderBooking::create(['table_id' => $table_id, 'restaurant_id' => $request->input('restaurant_id'), 'people_count' => $request->input('people_count')]);
                $booking->order()->save($order);
            }
            return new OrderResource($order);
        }
        throw new UnauthorizedException;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
