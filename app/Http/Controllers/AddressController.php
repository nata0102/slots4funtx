<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use DB;

class AddressController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try{
        return DB::transaction(function() use($request){
          $address = new Address;
          $address->name_address = $request->name_address;
          $address->business_name = $request->business_name;
          $address->city = $request->city;
          $address->country = $request->country;
          $address->client_id = $request->client_id;
          $address->active = 1;
          $created = $address->save();
          if ($created) {
            return response()->json(200);
          }else {
            return response()->json(__("Oops! there was an error, please try again later."), '422');
          }
        });
      }catch(\Exception $e){
        $cad = 'Oops! there was an error, please try again later.';
        $message = $e->getMessage();
        $pos = strpos($message, 'name_address');
        if ($pos != false)
            $cad = "The address field is required.";
        return response()->json(__($cad), '422');
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      try{
        return DB::transaction(function() use($id, $request){
          $address = Address::find($id);
          $address->name_address = $request->name_address;
          $address->business_name = $request->business_name;
          $address->city = $request->city;
          $address->country = $request->country;
          $created = $address->save();
          if ($created) {
            return response()->json(200);
          }else {
            return response()->json(__("Oops! there was an error, please try again later."), '422');
          }
      });
      }catch(\Exception $e){
        $cad = 'Oops! there was an error, please try again later.';
        $message = $e->getMessage();
        $pos = strpos($message, 'name_address');
        if ($pos != false)
            $cad = "The address field is required.";
        return response()->json(__($cad), '422');
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      try{
        return DB::transaction(function() use($id){
          $address = Address::find($id);
          $address->active = $address->active == 0 ? 1 : 0;
          $destroy = $address->save();
          if ($destroy) {
            return response()->json(200);
          }else {
            return response()->json(__("Oops! there was an error, please try again later."), '422');
          }
      });
      }catch(\Exception $e){
        return response()->json(__("Oops! there was an error, please try again later."), '422');
      }
    }
}
