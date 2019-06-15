<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order as OrderResource;
use App\Order;
use App\Restaurant;
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
            return OrderResource::collection(Order::where('client_id', $user->id)->orderBy('order_time', 'DESC')->get());
        } elseif ($user->isAdmin()) {
            return OrderResource::collection(Order::all()->get());
        } elseif ($user->isOwner()) {
            $owner_restaurants = Restaurant::where('owner_id', $user->id)->pluck('id');
            return OrderResource::collection(Order::whereIn('restaurant_id', $owner_restaurants)->get());
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
            return new OrderResource(Order::create([
                'restaurant_id' => $request->input('restaurant_id'),
                'client_id' => $user->id,
                'order_time' => Carbon::createFromTimestampUTC($request->input('order_time')),
                'order_status' => '1',
                'menu_id' => '1',
            ]));
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
