<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part;
use DB;
use File;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $parts = Part::all();
      return view('parts.index',compact('parts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $types =  DB::table('lookups')->where('type','part_type')->get();
      $protocols =  DB::table('lookups')->where('type','part_protocol')->get();
      $status =  DB::table('lookups')->where('type','status_parts')->get();
      return view('parts.create',compact('types','protocols','status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $part = new Part;
      $part->brand = $request->brand;
      $part->model = $request->model;
      $part->serial = $request->serial;
      $part->price = $request->price;
      $part->weight = $request->weight;
      $part->active = $request->active;

      $part->lkp_type_id = $request->type;
      $part->lkp_protocol_id = $request->protocol;
      $part->lkp_status_id = $request->status;

      $part->description = $request->description;

      if($request->image){
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($pattern)-1;
        for($i=0;$i < 10;$i++)
            $key .= $pattern{mt_rand(0,$max)};
        $key = $key.strtotime(date('Y-m-d H:i:s'));

        $fileName = $key.'.'.$request->file('image')->getClientOriginalExtension();
        $part->image = $fileName;
        $request->file('image')->move(
            public_path().'/images/part/', $fileName
        );
      }

      $created = $part->save();

      if ($created) {
        $notification = array(
          'message' => 'Pieza añadida con exito!',
          'alert-type' => 'success'
        );
      }else {
        $notification = array(
          'message' => 'Hubo un error. Intentalo de nuevo',
          'alert-type' => 'error'
        );
      }
      return redirect()->action('PartController@index')->with($notification);

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
      $part = Part::find($id);
      return view('parts.edit',compact('part'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
