<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MachineBrand;
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
      return view('machineBrand.index',compact('brands'));
    }

    public function searchWithFilters($params){
      $res = MachineBrand::where('model','LIKE',"%{$params['model']}%")->where('brand','LIKE',"%{$params['brand']}%")->where('weight','LIKE',"%{$params['weight']}%")->where('active',$params['active'])->get();
      return $res;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('machineBrand.create');
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
        'model' => 'required',
        'weight' => 'required|numeric',
      ]);

      $brand = MachineBrand::where('model',$request->model)->where('brand',$request->brand)->where('weight',$request->weight)->first();
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
      $brand = MachineBrand::find($id);
      return view('machineBrand.edit',compact('brand'));
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
        'model' => 'required',
        'weight' => 'required|numeric',
      ]);

      $brand = MachineBrand::where('model',$request->model)->where('brand',$request->brand)->where('weight',$request->weight)->where('id','!=',$id)->first();
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
          $brand->weight = $request->weight;
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
