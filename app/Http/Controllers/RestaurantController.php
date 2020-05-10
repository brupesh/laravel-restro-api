<?php

namespace App\Http\Controllers;

use App\Restaurant;
use Illuminate\Http\Request;
use Validator;
use App\Items;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Restaurant::all();
        return $data;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $out = [
            "status" => "success",
            "code" => 200,
            "data" => []
        ];
        $rules = array(
            'name'       => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $out["staus"] = "failed";
            $out["code"] = 400;
            $out["data"] = [
                "message" => $validator
            ];
            return $out;
        } else {
            // store
            $rest = new Restaurant;
            $rest->name = $request->get('name');
            $rest->save();

            $out["data"] = [
                "message" => "Reataurent Successfully created"
            ];
            return $out;
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        //
    }

    public function Items($rest_id)
    {
        # code...
        $item = Restaurant::find($rest_id)->items;
        return $item;
    }

}
