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
             $parts = Part::where('active',1)->orderBy('id','desc')->take(20)->get();
          break;
      }
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
                if($b_status == true && $b_brand == true)
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
      $this->validate($request, [
        'type' => 'required',
        'price' => 'required|numeric',
      ]);

      try{
        $transaction = DB::transaction(function() use($request){
          $part = new Part;
          $part->brand = $request->brand;
          $part->model = $request->model;
          $part->serial = $request->serial;
          $part->price = $request->price;
          $part->weight = $request->weight;
          $part->active = 1;

          $part->lkp_type_id = $request->type;
          $part->lkp_protocol_id = $request->protocol;
          $part->lkp_status_id = $request->status;

          $part->description = $request->description;

          if($request->image){
            $part->image = $this->saveGetNameImage($request->image,'/images/part/');
          }
          $created = $part->save();
          if ($created) {
            $notification = array(
              'message' => 'Successful!!',
              'alert-type' => 'success'
            );
            return redirect()->action('PartController@index');
          }else {
            $notification = array(
              'message' => 'Oops! there was an error, please try again later.',
              'alert-type' => 'error'
            );
          }
          return back()->with($notification);
          });
        }catch(\Exception $e){
          $transaction = array(
            //'message' => 'Machine Not Saved:'.$e->getMessage(),
              'message' => 'Oops! there was an error, please try again later.',
              'alert-type' => 'error'
          );


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
      $this->validate($request, [
        'type' => 'required',
        'price' => 'required|numeric',
      ]);



      $part = Part::find($id);
      $part->brand = $request->brand;
      $part->model = $request->model;
      $part->serial = $request->serial;
      $part->price = $request->price;
      $part->weight = $request->weight;

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
