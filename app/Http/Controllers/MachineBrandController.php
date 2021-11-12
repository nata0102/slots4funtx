<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MachineBrand;
use App\Models\Part;
use DB;
use File;
use Input;

class MachineBrandController extends Controller
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
          $brands = $this->searchWithFilters($request->all());
        break;
        default:
          $brands = MachineBrand::where('active',1)->orderBy('id','desc')->take(20)->get();
        break;
      }
      $types =  DB::table('lookups')->where('type','brand_type')->where('active',1)->get();
      $brands_types =  DB::table('machine_brands')->where('lkp_type_id',53)->where('active',1)
                      ->orderBy('brand')->groupBy('brand')->select('brand')->get();
      $brands_types2 =  DB::table('machine_brands')->where('lkp_type_id',54)->where('active',1)
                      ->orderBy('brand')->groupBy('brand')->select('brand')->get();

      return view('machineBrand.index',compact('brands','types','brands_types','brands_types2'));
    }

    public function searchWithFilters($params){
      $res = MachineBrand::with('type')
      ->model($params['model'])
      ->brand($params['brand_type'])
      ->type($params['type'])
      ->where('active',$params['active'])->orderBy('brand')->orderBy('model')->get();
      return $res;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $types =  DB::table('lookups')->where('type','brand_type')->where('active',1)->get();
      $parts = Part::where('active',1)->orderBy('id','desc')->get();
      return view('machineBrand.create', compact('types','parts'));
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
        'brand' => 'required',
        'lkp_type_id' => 'required',
        'weight' => 'numeric',
      ]);

      $brand = MachineBrand::where('lkp_type_id',$request->lkp_type_id)->where('model',$request->model)->where('brand',$request->brand)->first();
      if($brand){
        $notification = array(
            'message' => 'There is already a record with the same data.',
            'alert-type' => 'info'
        );
        return back()->with($notification)->withInput($request->all());
      }

      try {
        return DB::transaction(function() use($request){
          $brand = new MachineBrand;
          $brand->brand = $request->brand;
          $brand->model = $request->model;
          $brand->weight = $request->weight;
          $brand->lkp_part_id = $request->part_id;
          $brand->lkp_type_id = $request->lkp_type_id;
          $brand->active = 1;
          $created = $brand->save();
          if ($created) {
            $notification = array(
              'message' => 'Successful!!',
              'alert-type' => 'success'
            );
            return redirect()->action('MachineBrandController@index')->with($notification);
          }else {
            $notification = array(
              'message' => 'Oops! there was an error, please try again later.',
              'alert-type' => 'error'
            );
            return back()->with($notification)->withInput($request->all());
          }
        });
      } catch (\Exception $e) {
        $cad='Oops! there was an error, please try again later.';
        $message = $e->getMessage();
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
      $brand = MachineBrand::find($id);
      return view('machineBrand.show',compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $types =  DB::table('lookups')->where('type','brand_type')->where('active',1)->get();
      $brand = MachineBrand::find($id);
      $parts = Part::where('active',1)->orderBy('id','desc')->get();

      return view('machineBrand.edit',compact('brand','types','parts'));
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
        'brand' => 'required',
        'lkp_type_id' => 'required',
        'weight' => 'numeric',
      ]);

      $brand = MachineBrand::where('lkp_type_id',$request->lkp_type_id)->where('model',$request->model)->where('brand',$request->brand)->where('id','!=',$id)->first();
      if($brand){
        $notification = array(
            'message' => 'There is already a record with the same data.',
            'alert-type' => 'info'
        );
        return back()->with($notification)->withInput($request->all());
      }

      try {
        return DB::transaction(function() use($request, $id){
          $brand = MachineBrand::find($id);
          $brand->brand = $request->brand;
          $brand->model = $request->model;
          $brand->lkp_part_id = $request->part_id;
          $brand->weight = $request->weight;
          $brand->lkp_type_id = $request->lkp_type_id;
          $created = $brand->save();
          if ($created) {
            $notification = array(
              'message' => 'Successful!!',
              'alert-type' => 'success'
            );
            return redirect()->action('MachineBrandController@index')->with($notification);
          }else {
            $notification = array(
              'message' => 'Oops! there was an error, please try again later.',
              'alert-type' => 'error'
            );
            return back()->with($notification)->withInput($request->all());
          }
        });
      } catch (\Exception $e) {
        $cad='Oops! there was an error, please try again later.';
        $message = $e->getMessage();
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
          $brand = MachineBrand::find($id);
          $brand->active = $brand->active == 0 ? 1 : 0;
          $destroy = $brand->save();
          if ($destroy) {
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
