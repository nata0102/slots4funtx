<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MachineBrand;
use App\Models\GameCatalog;
use App\Models\GameBrand;
use App\Models\Lookup;
use Illuminate\Support\Facades\DB;

class GameCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $res = $this->searchWithFilters($request->all());
        return view('game_catalog.index',compact('res'));
    }

    public function searchWithFilters($params){
        $qry = "select *,(select value from lookups where id=game_catalog.lkp_type_id) as type from game_catalog ";
        if(count($params)>0){
            $qry.= " where ";
            if (array_key_exists('active', $params) && $params['active']!=""){
                if(substr($qry, -6) != "where ")
                    $qry .= " and ";
                $qry .= "active = ". $params['active'];
            }
            if (array_key_exists('name', $params) && $params['name']!=""){
                if(substr($qry, -6) != "where ")
                    $qry .= " and ";
                $qry .= "  name like '%".$params['name']."%'";
            }
        }else
            $qry .= " where active = 1 ";
        $qry .= " order by name;";
        return DB::select($qry);
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
      $types = Lookup::where('type','game_type')->orderBy('value')->get();
      $brands = MachineBrand::where('lkp_type_id',53)->orderBy('brand')->orderBy('model')->get();
      return view('game_catalog.create',compact('brands','types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
      try {
            return DB::transaction(function() use($request){
              $arr = $request->except('_token','brands_ids');
              $created = GameCatalog::create($arr);
              if (array_key_exists('brands_ids', $request->all())){
                  foreach ($request->brands_ids as $brand_id)
                        GameBrand::create(['game_catalog_id' => $created->id,'machine_brand_id'=>$brand_id]);
              }
              if ($created) {
                $notification = array(
                  'message' => 'Successful!!',
                  'alert-type' => 'success'
                );
                return redirect()->action('GameCatalogController@index')->with($notification);
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
            $cad = $e->getMessage();
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
        $res = GameCatalog::findOrFail($id);
        $brands = GameBrand::with('brand')->where('game_catalog_id',$id)->get();
        return view('game_catalog.show',compact('res','brands'));
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
        $res = GameCatalog::findOrFail($id);
        $types = Lookup::where('type','game_type')->orderBy('value')->get();
        $brands = MachineBrand::where('lkp_type_id',53)->orderBy('brand')->orderBy('model')->get();
        $game_brands = GameBrand::where('game_catalog_id',$id)->get();
        $brands_ids = [];
        foreach ($game_brands as $b)
            array_push($brands_ids,(string) $b->machine_brand_id);
        return view('game_catalog.edit',compact('brands_ids','res','brands','types'));
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
        try {
            return DB::transaction(function() use ($request, $id){
              $arr = $request->except('_method','_token','brands_ids','game_name','game_license');
              $game = GameCatalog::findOrFail($id);
              $game->update($arr);
              $game->save();
              if (array_key_exists('brands_ids', $request->all())){
                  GameBrand::where('game_catalog_id',$id)->delete();
                  foreach ($request->brands_ids as $brand_id)
                        GameBrand::create(['game_catalog_id' => $game->id,'machine_brand_id'=>$brand_id]);
              }
              if ($game) {
                $notification = array(
                  'message' => 'Successful!!',
                  'alert-type' => 'success'
                );
                return redirect()->action('GameCatalogController@index')->with($notification);
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
            $cad = $e->getMessage();
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
            $transaction = DB::transaction(function() use ($id){
                $game = GameCatalog::findOrFail($id);
                $game->active = $game->active == 0 ? 1 : 0;
                if($game->save())
                    return response()->json(200);
                else
                    return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
            });
            return $transaction;
        }catch(\Exception $e){
            return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
        }
    }
}
