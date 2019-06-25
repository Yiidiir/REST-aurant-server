<?php

namespace App\Http\Controllers;

use App\Http\Resources\RestaurantOwner;
use App\Restaurant;
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
        //
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
}
