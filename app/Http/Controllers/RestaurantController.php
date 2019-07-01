<?php

namespace App\Http\Controllers;

use App\Http\Resources\RestaurantOwner;
use App\Order;
use App\OrderBooking;
use App\Restaurant;
use App\Table;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Resources\Restaurant as RestaurantResource;
use App\Http\Resources\RestaurantOwner as RestaurantResourceOwner;
use Illuminate\Support\Facades\Auth;


class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('api')->user();
        if ($user->isOwner()) {
            return RestaurantResourceOwner::collection(Restaurant::where('owner_id', $user->id)->get());
        }
        return RestaurantResource::collection(Restaurant::all());
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
        if ($user->isOwner()) {
            return Restaurant::create([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'class' => $request->input('class'),
                'owner_id' => $user->id,
                'work_hours' => 'a:8:{s:6:"monday";a:2:{i:0;s:11:"10:00-16:00";i:1;s:11:"18:00-23:00";}s:7:"tuesday";a:2:{i:0;s:11:"10:00-16:00";i:1;s:11:"18:00-23:00";}s:9:"wednesday";a:1:{i:0;s:11:"09:00-12:00";}s:8:"thursday";a:2:{i:0;s:11:"10:00-16:00";i:1;s:11:"18:00-23:00";}s:6:"friday";a:0:{}s:8:"saturday";a:2:{i:0;s:11:"10:00-16:00";i:1;s:11:"18:00-23:00";}s:6:"sunday";a:2:{i:0;s:11:"10:00-16:00";i:1;s:11:"18:00-23:00";}s:10:"exceptions";a:5:{s:10:"2019-11-11";a:1:{i:0;s:11:"09:00-12:00";}s:5:"01-01";a:0:{}s:5:"07-05";a:0:{}s:5:"11-01";a:0:{}s:5:"03-08";a:1:{i:0;s:11:"11:00-13:00";}}}',
            ]);
        }
        throw new UnauthorizedException;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Restaurant $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        $user = Auth::guard('api')->user();
        if ($user->isOwner()) {
            return new RestaurantResourceOwner($restaurant);
        }
        return new RestaurantResource($restaurant);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Restaurant $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $restaurant->update($request->all());

        return response()->json($restaurant, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Restaurant $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();
        return response()->json(null, 204);
    }

    public function updateWorkHours(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);
        $restaurant->update([
            'work_hours' => serialize($request->input('work_hours'))]);
        return response(new RestaurantOwner($restaurant));
    }

    public function openStatus(Request $request, $id, $date, $hour)
    {
        $restaurant = Restaurant::find($id);
        $datetimex = $date . ' ' . $hour . ':00';
        $isOpen = $restaurant->isOpenAt(new DateTime($datetimex));
        $nextOpen = $restaurant->nextOpenAt(new DateTime($datetimex));
        return response()->json(['time' => $datetimex, 'open' => $isOpen, 'next' => $nextOpen]);
    }

    public function getAvailableTables(Request $request, $id, $class, $peoplecount, $date, $hour)
    {
        $passed_date = Carbon::parse($date . ' ' . $hour);
        $restaurant = Restaurant::find($id);
        $orders = Order::where('restaurant_id', $id)->where('orderDb_type', 'App\OrderBooking')->where('order_time', '>=',
            Carbon::parse($passed_date)->toDateTimeString()
        )->where('order_time', '<',
            Carbon::parse($passed_date)->addHours(3)->toDateTimeString()
        )->where('order_status', 1)->pluck('orderDb_id');
        $taken_tables_ids = OrderBooking::find($orders)->pluck('table_id');
        $all_tables = Table::where('restaurant_id', $id)->get();
        $free_tables = Table::where('restaurant_id', $id)->whereNotIn('id', $taken_tables_ids)->where('available', 1)
            ->where('class', '=', $class)->where('capacity_min', '<=', $peoplecount)
            ->where('capacity_max', '>=', $peoplecount)->pluck('id');
//        $tables = $restaurant->tables()->whereNotIn('id', $orders)->get();
        return response()->json($free_tables);
    }
}
