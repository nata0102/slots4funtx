<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Lookup;
use App\Models\Machine;
use DB;
use File;
use Input;

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
          $parts = Part::where('active',1)->orderBy('id','desc')->with('machine.brand','brand')->take(20)->get();
        break;
      }
      $types =  DB::table('lookups')->where('type','part_type')->where('active',1)->orderBy('value')->get();
      $brands =  DB::table('machine_brands')->where('lkp_type_id',53)->where('active',1)->orderBy('brand')->orderBy('model')->get();
      $status =  DB::table('lookups')->where('type','status_parts')->where('active',1)->orderBy('value')->get();

      return view('parts.index',compact('parts','types','brands','status'));
    }

    public function searchWithFilters($params){

      $res = Part::where('active',$params['active'])
      ->status1($params['status'])
      ->type1($params['type'])
      ->brand($params['brand'])->get();
      return $res;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $brands =  DB::table('machine_brands')->where('lkp_type_id',54)->where('active',1)->get();
      $types =  DB::table('lookups')->where('type','part_type')->where('active',1)->get();
      $protocols =  DB::table('lookups')->where('type','part_protocol')->where('active',1)->get();
      $status =  DB::table('lookups')->where('type','status_parts')->where('active',1)->get();
      $machines = Machine::with('game')->where('active',1)->orderBy('serial')->get();
      return view('parts.create',compact('types','protocols','status','machines','brands'));
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
        'price' => 'numeric|nullable',
        'serial' => 'unique:parts,serial|nullable',
      ]);

      try{
        return DB::transaction(function() use($request){
          $part = new Part;
          $part->brand_id = $request->brand_id;
          $part->serial = $request->serial;
          $part->price = $request->price;
          $part->active = 1;
          $part->lkp_type_id = $request->type;
          $part->lkp_protocol_id = $request->protocol;
          $part->lkp_status_id = $request->status;
          $part->description = $request->description;
          $part->machine_id = $request->machine_id;
          if($request->image){
            $part->image = $this->saveGetNameImage($request->image,'/images/part/');
          }
          $created = $part->save();
          if ($created) {
            $notification = array(
              'message' => 'Successful!!',
              'alert-type' => 'success'
            );

            ///historial de pieza
            $this->insertPartHistory($part->id);

            return redirect()->action('PartController@index')->with($notification);
          }else {
            $notification = array(
              'message' => 'Oops! there was an error, please try again later.',
              'alert-type' => 'error'
            );
            return back()->with($notification)->withInput($request->all());
          }
        });
      }catch(\Exception $e){
        $cad='Oops! there was an error, please try again later.';
        $message = $e->getMessage();
        $pos = strpos($message, 'part.serial');
        if ($pos != false)
            $cad = "The Serial must be unique.";
        $transaction = array(
            'message' => $cad,
            'alert-type' => 'error'
        );
        return back()->with($transaction)->withInput($request->all());
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
      $part = Part::with('type','protocol','brand','status','machine.brand')->find($id);
      return view('parts.show',compact('part'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $brands =  DB::table('machine_brands')->where('lkp_type_id',54)->where('active',1)->get();
      $part = DB::table('parts')->where('id',$id)->where('active',1)->where('active',1)->first();
      $types =  DB::table('lookups')->where('type','part_type')->where('active',1)->get();
      $protocols =  DB::table('lookups')->where('type','part_protocol')->where('active',1)->get();
      $status =  DB::table('lookups')->where('type','status_parts')->where('active',1)->get();
      $machines = Machine::with('game')->where('active',1)->orderBy('serial')->get();
      $part = Part::where('id',$id)->with('machine')->first();
      return view('parts.edit',compact('part','types','protocols','status','machines','brands'));
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
        'price' => 'numeric|nullable',
        'serial' => 'nullable|unique:parts,serial,'.$id,
      ]);

      try{
        return DB::transaction(function() use($request, $id){
          $part = Part::find($id);
          $part->brand_id = $request->brand_id;
          $part->serial = $request->serial;
          $part->price = $request->price;
          $part->lkp_type_id = $request->type;
          $part->lkp_protocol_id = $request->protocol;
          $part->lkp_status_id = $request->status;
          $part->description = $request->description;
          $part->machine_id = $request->machine_id;
          if($request->image){
            if($part->image != NULL && file_exists(public_path().'/images/part/'.$part->image)){
              unlink(public_path().'/images/part/'.$part->image);
            }
            $part->image = $this->saveGetNameImage($request->image,'/images/part/');
          }
          $created = $part->save();
          if ($created) {
            ///historial de pieza
            $this->insertPartHistory($part->id);
            $notification = array(
              'message' => 'Successful!!',
              'alert-type' => 'success'
            );
            return redirect()->action('PartController@index')->with($notification);
          }else {
            $notification = array(
              'message' => 'Oops! there was an error, please try again later.',
              'alert-type' => 'error'
            );
            return back()->with($notification)->withInput($request->all());
          }
        });
      }catch(\Exception $e){
        $message = $e->getMessage();
        $cad = 'Oops! there was an error, please try again later.' .$message;
        $pos = strpos($message, 'part.serial');
        if ($pos != false)
            $cad = "The Serial must be unique.";
        $transaction = array(
            'message' => $cad,
            'alert-type' => 'error'
        );
        return back()->with($transaction)->withInput($request->all());
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
          $part = Part::find($id);
          $part->active = $part->active == 0 ? 1 : 0;
          $destroy = $part->save();
          if ($destroy) {
            $this->insertPartHistory($part->id);
            return response()->json(200);
          }else {
            return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
          }
      });
      }catch(\Exception $e){
        return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
      }
    }

    public function active($id)
    {
      try{
        return DB::transaction(function() use($id){
          $part = Part::find($id);
          $part->active = $part->active == 0 ? 1 : 0;
          $update = $part->save();
          if ($update) {
            $this->insertPartHistory($part->id);
            return response()->json(200);
          }else {
            return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
          }
      });
      }catch(\Exception $e){
        return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
      }

    }

}
