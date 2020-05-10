<?php

namespace App\Http\Controllers;

use App\Items;
use Illuminate\Http\Request;
use Validator;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Items::all();
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
            'rest_id'       => 'required',
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
            $item = new Items;
            $item->name = $request->get('name');
            $item->rest_id = $request->get('rest_id');
            $item->save();

            $out["data"] = [
                "message" => "Item Successfully created"
            ];
            return $out;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Items $items)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Items  $items
     * @return \Illuminate\Http\Response
     */
    public function destroy(Items $items)
    {
        //
    }


}
