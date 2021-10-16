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
    public function index(Request $request)
    {
      switch($request->option){
          case 'all':
             $parts = $this->searchWithFilters($request->all());

          break;
          default:
             $parts = Part::all();
          break;
      }
      $parts = Part::all();
      return view('parts.index',compact('parts'));
    }

    public function searchWithFilters($params){
        $res = [];
        $aux = Part::with([
            'status' => function ($query) use($params){
                if($params['status'])
                    $query->where('value', 'LIKE', "%{$params['status']}%");
            },
            'brand' => function($query) use ($params){
                if($params['brand'])
                    $query->where('brand', 'LIKE', "%{$params['brand']}%")
                    ->orWhere('model', 'LIKE', "%{$params['brand']}%")
                    ->orWhere('weight', 'LIKE', "%{$params['brand']}%");
            }])->where('active',1)->get();
            foreach ($aux as $a) {
                $b_owner = true;
                if($params['owner']){
                    if($a->owner == null)
                        $b_owner = false;
                }
                $b_status = true;
                if($params['status']){
                    if($a->status == null)
                        $b_status = false;
                }
                $b_brand = true;
                if($params['brand']){
                    if($a->brand == null)
                        $b_brand = false;
                }
                if($b_owner == true && $b_status == true && $b_brand == true)
                    array_push($res,$a);
            }
        return $res;
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
          'message' => 'Successful!!',
          'alert-type' => 'success'
        );
      }else {
        $notification = array(
          'message' => 'Oops! there was an error, please try again later.',
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
      $part = DB::table('parts')->where('id',$id)->first();
      $types =  DB::table('lookups')->where('type','part_type')->get();
      $protocols =  DB::table('lookups')->where('type','part_protocol')->get();
      $status =  DB::table('lookups')->where('type','status_parts')->get();
      return view('parts.edit',compact('part','types','protocols','status'));
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
      $part = Part::find($id);
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
        if($part->image){
          unlink(public_path().'/images/part/'.$part->image);
        }
        $part->image = $this->saveGetNameImage($request->image,'/images/part/');
      }

      $created = $part->save();

      if ($created) {
        $notification = array(
          'message' => 'Successful!!',
          'alert-type' => 'success'
        );
      }else {
        $notification = array(
          'message' => 'Oops! there was an error, please try again later',
          'alert-type' => 'error'
        );
      }
      return redirect()->action('PartController@index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $part = Part::find($id);
        $part->active = 0;
        $destroy = $part->save();
        if ($destroy) {
          $notification = array(
            'message' => 'Successful!!',
            'alert-type' => 'success'
          );
        }else {
          $notification = array(
            'message' => 'Oops! there was an error, please try again later',
            'alert-type' => 'error'
          );
        }
        return redirect()->action('PartController@index')->with($notification);
    }
}
