<?php

namespace App\Http\Controllers;

use App\FoodMenu;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\Food as FoodResource;
use App\Order;
use App\OrderBooking;
use App\OrderDelivery;
use App\PTransaction;
use App\Restaurant;
use App\Table;
use Carbon\Carbon;
use function GuzzleHttp\Promise\all;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Stripe\Stripe;

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
                'order_time' => Carbon::createFromFormat('Y-m-d H:i', $request->input('order_date') . ' ' . $request->input('order_time'), 'Africa/Algiers')->addHours(1)->timestamp,
                'order_status' => '4',
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

    public function changeStatus(Request $request, $id, $new_status)
    {
        $user = Auth::user();
        if ($user->isClient()) {
            $new_status = 0;
        }
        $order = Order::find($id);
        if ($order->order_status != 2 && $order->order_status != 0) {
            $order->update(['order_status' => $new_status]);
        }
        return response()->json($order);
    }

    public function chargeMoney(Request $request, $id)
    {
        $transaction = PTransaction::create([
            'id' => $request->input('t_id'),
            'payer_name' => $request->input('payer_name'),
            'payer_ip' => $request->input('payer_ip'),
            'payment_timestamp' => $request->input('payment_timestamp'),
            'card_brand' => $request->input('card_brand'),
            'card_country' => $request->input('card_country'),
            'card_zip' => $request->input('card_zip'),
            'card_exp' => $request->input('card_exp'),
            'card_id' => $request->input('card_id'),
            'card_last4' => $request->input('card_last4'),
            'order_id' => $id,
        ]);

        $order = Order::find($transaction->order_id);

        $foods = $order->menu->foods->pluck('id');
        $price = 0;
        foreach ($foods as $food) {
            $price = $price + \App\Food::find($food)->price;
        }

        Stripe::setApiKey('sk_test_VCiSxV23jAQ58f1q8d2EdRnm00CEQLvDMI');

        $token = $transaction->id;
        $charge = \Stripe\Charge::create([
            'amount' => $price * 100,
            'currency' => 'dzd',
            'description' => 'Charge for order #' . $transaction->order_id,
            'source' => $token,
            'receipt_email' => Auth::user()->email,
        ]);
        $transaction->update(['receipt_url' => $charge->receipt_url]);

        $order->update(['order_status' => '1']);
        return $transaction;
    }

    public function ordersOfRestaurant(Request $request, $id)
    {
        $user = Auth::guard('api')->user();
        if ($user->isClient()) {
            return OrderResource::collection(Order::where('client_id', $user->id)->orderBy('id', 'DESC')->get());
        } elseif ($user->isAdmin()) {
            return OrderResource::collection(Order::all()->get());
        } elseif ($user->isOwner()) {
            $owner_restaurants = Restaurant::where('owner_id', $user->id)->pluck('id');
            return OrderResource::collection(Order::whereIn('restaurant_id', $owner_restaurants)->where('restaurant_id', $id)->orderBy('id', 'DESC')->get());
        }
        throw new UnauthorizedException;
    }
}
