<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Lookup;
use App\Models\LkpPartBrand;
use App\Models\PartDetail;
use App\Models\Machine;
use App\Models\PartImage;
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
          $parts = Part::where('active',1)->orderBy('id','desc')->with('machine.owner','machine.game','brand','details.detail')->take(20)->get();
        break;
      }
      $types =  DB::table('lookups')->where('type','part_type')->where('active',1)->orderBy('value')->get();
      $qry = "select l.lkp_id,l.brand_id,b.brand,b.model from parts_lkp_brands l, machine_brands b
              where l.brand_id = b.id and b.active = 1 order by b.brand, b.model;";
      $brands = json_encode(DB::select($qry));
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
      if( url()->previous() != url()->current() ){
        session()->forget('urlBack');
        session(['urlBack' => url()->previous()]);
      }

      $qry = "select l.lkp_id,l.brand_id,b.* from parts_lkp_brands l, machine_brands b
      where l.brand_id = b.id and b.active = 1  order by b.brand, b.model;";
      $brands = json_encode(DB::select($qry));
      $types =  DB::table('lookups')->where('type','part_type')->where('active',1)->orderBy('value')->get();
      $qry = "select l.*, pl.part_id from lookups l, parts_lkp_brands pl
      where l.id = pl.lkp_id and l.active = 1 and pl.part_id is not null order by l.value;";
      $details = json_encode(DB::select($qry));
      $status =  DB::table('lookups')->where('type','status_parts')->where('active',1)->orderBy('value')->get();
      $machines = Machine::with('game')->where('active',1)->orderBy('serial')->get();
      return view('parts.create',compact('types','details','status','machines','brands'));
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
        'lkp_type_id' => 'required',
        'lkp_status_id' => 'required',
        'price' => 'numeric|nullable',
        'serial' => 'unique:parts,serial|nullable',
      ]);

      try{
        return DB::transaction(function() use($request){
          if($request->serial != null){
            $arr = $request->except('_token','old_brand_id','details_ids','old_details_ids');
            $arr['serial'] = strtoupper($arr['serial']);
          }
          else
            $arr = $request->except('_token','old_brand_id','details_ids','old_details_ids','serial');
          $arr['description'] = strtoupper($arr['description']);
          
          $part = Part::create($arr);
          if(array_key_exists('details_ids', $request->all())){
              PartDetail::where('part_id',$part->id)->delete();
              foreach ($request->details_ids as $detail_id) 
                  PartDetail::create(['part_id'=>$part->id,'lkp_detail_id'=>$detail_id]);
          }
          /*if($request->image){
            $part->image = $this->saveGetNameImage($request->image,'/images/part/');
          }*/
          if ($part) {
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
      }catch(\Exception $e){return $e->getMessage();
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
      if( url()->previous() != url()->current() ){
        session()->forget('urlBack');
        session(['urlBack' => url()->previous()]);
      }
      $part = Part::with('type','brand','status','machine.brand','details.detail')->find($id);
      $images = PartImage::where('part_id',$id)->get();
      $aux = "";
      foreach ($part->details as $detail) 
         $aux .= $detail->detail->value."\n";
      $part->cad_details = $aux;
      return view('parts.show',compact('part','images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if( url()->previous() != url()->current() ){
        session()->forget('urlBack');
        session(['urlBack' => url()->previous()]);
      }
      $qry = "select l.lkp_id,l.brand_id,b.* from parts_lkp_brands l, machine_brands b
      where l.brand_id = b.id and b.active = 1 order by b.brand, b.model;";
      $brands = json_encode(DB::select($qry));
      $types =  DB::table('lookups')->where('type','part_type')->where('active',1)->orderBy('value')->get();
      $qry = "select l.*, pl.part_id from lookups l, parts_lkp_brands pl
      where l.id = pl.lkp_id and l.active = 1 and pl.part_id is not null order by l.value;";
      $details = json_encode(DB::select($qry));
      $status =  DB::table('lookups')->where('type','status_parts')->where('active',1)->orderBy('value')->get();
      $machines = Machine::with('game')->where('active',1)->orderBy('serial')->get();
      $details_part = DB::table('parts_details')->where('part_id',$id)->get();
      $details_aux_ids = [];
      foreach ($details_part as $detail)
          array_push($details_aux_ids, (string)$detail->lkp_detail_id);
      $details_aux_ids = json_encode($details_aux_ids);
      $part = Part::where('id',$id)->with('machine','machine.game','brand')->first();
      return view('parts.edit',compact('part','types','details','status','machines','brands',
        'details_aux_ids'));
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
        'lkp_type_id' => 'required',
        'lkp_status_id' => 'required',
        'price' => 'numeric|nullable',
        'serial' => 'nullable|unique:parts,serial,'.$id,
      ]);

      try{
        return DB::transaction(function() use($request, $id){
          $part = Part::findOrFail($id);
          if($request->serial != null){
            $arr = $request->except('_method','_token','old_brand_id');
            $arr['serial'] = strtoupper($arr['serial']);
          }else
            $arr = $request->except('_method','_token','old_brand_id','serial');
          $arr['description'] = strtoupper($arr['description']);
          $part->update($arr);
          $part->save();
          /*if($request->image){
            if($part->image != NULL && file_exists(public_path().'/images/part/'.$part->image)){
              unlink(public_path().'/images/part/'.$part->image);
            }
            $part->image = $this->saveGetNameImage($request->image,'/images/part/');
          }*/
          if ($part) {
            if(array_key_exists('details_ids', $request->all())){
              PartDetail::where('part_id',$part->id)->delete();
              foreach ($request->details_ids as $detail_id) 
                  PartDetail::create(['part_id'=>$part->id,'lkp_detail_id'=>$detail_id]);
            }
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

    public function createByRank(){
      if( url()->previous() != url()->current() ){
        session()->forget('urlBack');
        session(['urlBack' => url()->previous()]);
      }
      $qry = "select * from parts_lkp_brands l, machine_brands b
      where l.brand_id = b.id and b.active = 1 order by b.brand, b.model;";
      $brands = json_encode(DB::select($qry));
      $types =  DB::table('lookups')->where('type','part_type')->where('active',1)->orderBy('value')->get();
      $details =  DB::table('lookups')->where('type','details')->where('active',1)->orderBy('value')->get();
      $status =  DB::table('lookups')->where('type','status_parts')->where('active',1)->orderBy('value')->get();
      return view('parts.createByRank',compact('types','details','status','brands'));
    }

    public function storeByRank(Request $request)
    {
        $this->validate($request, [
            'lkp_type_id' => 'required',
            'lkp_status_id' => 'required',
            'start_range' => 'required',
            'final_range' => 'required',
        ]);
        if($request->final_range < $request->start_range){
            $transaction = array(
                'message' => 'The final range must be greater than the initial range.',
                'alert-type' => 'error'
            );
            return back()->with($transaction)->withInput($request->all());
        }
        try{
            $transaction = DB::transaction(function() use($request){
                $arr = $request->only('start_range','final_range','serial');
                $arr['serial'] = strtoupper($arr['serial']);
                $arr_aux =  $request->except('_token','details_ids','old_details_ids');
                for($i = $arr['start_range']; $i<= $arr['final_range']; $i++) {
                    $arr_aux['serial'] = $arr['serial'] . $i;
                    $part = Part::create($arr_aux);
                    $this->insertPartHistory($part->id);
                    if(array_key_exists('details_ids', $request->all())){
                        PartDetail::where('part_id',$part->id)->delete();
                        foreach ($request->details_ids as $detail_id) 
                            PartDetail::create(['part_id'=>$part->id,'lkp_detail_id'=>$detail_id]);
                    }
                }
                $notification = array(
                  'message' => 'Successful!!',
                  'alert-type' => 'success'
                );
                return $notification;
            });

            return redirect()->action('PartController@index')->with($transaction);
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';
            $transaction = array(
                'message' => $cad,
                'alert-type' => 'error'
            );
        }

        return back()->with($transaction)->withInput($request->all());
    }


    public function gallery($id){

      $part = Part::with('type','protocol','brand','status','machine.brand')->find($id);
      $images = PartImage::where('part_id',$id)->get();
      return view('parts.images',compact('images','part'));
    }



    public function createImage(Request $request, $id){
      $notification = array(
          'message' => 'There is already a record with the same data.',
          'alert-type' => 'info'
      );

      try{
        return DB::transaction(function() use($request, $id){
          $image = new PartImage;
          $image->part_id = $id;
          if($request->image){
              $image->name_image = $this->saveGetNameImage($request->image,'/images/part brand/');
          }
          $image->save();
          $notification = array(
              'message' => 'Successful!!',
              'alert-type' => 'success'
          );
          return redirect() -> action('PartController@gallery',$id)->with($notification);
        });
      }catch(\Exception $e){
        $notification = array(
            'message' => 'Oops! there was an error, please try again later.',
            'alert-type' => 'error'
        );
      }
      return redirect() -> action('PartController@gallery',$id)->with($notification);
    }

    public function deleteImage($id){
      $image = PartImage::where('id',$id)->first();
      $notification = array(
          'message' => 'Oops! there was an error, please try again later.',
          'alert-type' => 'error'
      );
      if($image != NULL){
        try{
          return DB::transaction(function() use($image, $notification){
            if(file_exists(public_path() . '/images/part brand/'.$image->name_image))
            {
              unlink(public_path() . '/images/part brand/'.$image->name_image);
            }
            $destroy = $image->delete();
            if($destroy){
              $notification = array(
                'message' => 'Successful!!',
                'alert-type' => 'success'
              );
            }
            return redirect() -> action('PartController@gallery',$image->part_id)->with($notification);
          });


        }catch(\Exception $e){
          $notification = array(
            'message' => 'Oops! there was an error, please try again later.',
            'alert-type' => 'error'
          );
          return redirect() -> action('PartController@gallery',$image->part_id)->with($notification);
        }
      }
      return redirect() -> back()->with($notification);


    }


}
