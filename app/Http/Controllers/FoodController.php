<?php

namespace App\Http\Controllers;

use App\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            return Food::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'restaurant_id' => $request->input('restaurant_id'),
            ]);
        }
        throw new UnauthorizedException;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Food $food
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Food $food
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Food $food)
    {
        $food->update($request->all());

        return response()->json($food, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Food $food
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Food $food)
    {
        $food->update($request->all());

        return response()->json($food, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Food $food
     * @return \Illuminate\Http\Response
     */
    public function destroy(Food $food)
    {
        $foodDelete = $food->delete();
        if ($foodDelete) {
            return response()->json(['success' => 'Food Deleted'], 200);
        }
        return response()->json(['error' => 'Not found']
            , 422);
    }
}
