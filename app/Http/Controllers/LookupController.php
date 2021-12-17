<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lookup;
use App\Models\MachineBrand;
use App\Models\LkpPartBrand;
use Illuminate\Support\Facades\DB;

class LookupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $res = [];
      $types = [];
      switch ($request->option) {
          case 'type':
              $res = Lookup::where('type',$request->type)->orderBy('value')->get();
          break;
          default:
              $res = $this->searchWithFilters($request->all());
              $types = DB::table('lookups')->where('type','configuration')->where('band_add',1)->orderBy('value')->get();
              //$params = $request->all();
          break;
      }
      return view('lookups.index',compact('res','types'));
    }

    public function searchWithFilters($params){
        $qry = "select tab1.*, l2.id, l2.value, l2.lkp_city_id,
        (select value from lookups where id=l2.lkp_city_id) as city
        from(select l.key_value as p_key_value, value as p_value from lookups l where band_add = 1 and type='configuration') as tab1, lookups l2  where l2.type = tab1.p_key_value";
        if (array_key_exists('type', $params) && $params['type']!="")
            $qry .= " and tab1.p_key_value ='".$params['type']."'";
        if (array_key_exists('value', $params))
            $qry .= " and l2.value like '%".$params['value']."%'";
        if(!array_key_exists('active', $params))
            $qry .= " and l2.active = 1";
        else{
            if($params['active'] == null || $params['active'] == "")
                $qry .= " and l2.active = 1";
            else
                $qry .= " and l2.active = ".$params['active'];
        }
        $qry .= " order by p_value, value;";
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
        $types =  DB::table('lookups')->where('type','configuration')->where('band_add',1)->orderBy('value')->get();
        $cities =  DB::table('lookups')->where('type','cities')->where('band_add',0)->orderBy('value')->get();
        $brands = MachineBrand::where('lkp_type_id',54)->orderBy('brand')->orderBy('model')->get();
        return view('lookups.create',compact('types','cities','brands'));
    }

    public function getKeyValue($cadena){
        $cadena = str_replace(" ", "_", $cadena);
        $cadena = strtolower($cadena);
        //Reemplazamos la A y a
        $cadena = str_replace(array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),$cadena);

        //Reemplazamos la E y e
        $cadena = str_replace(array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),$cadena );

        //Reemplazamos la I y i
        $cadena = str_replace(array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),$cadena );

        //Reemplazamos la O y o
        $cadena = str_replace(array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),$cadena );

        //Reemplazamos la U y u
        $cadena = str_replace(array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),$cadena );

        //Reemplazamos la N, n, C y c
        $cadena = str_replace(array('Ñ', 'ñ', 'Ç', 'ç'),array('N', 'n', 'C', 'c'),$cadena);
        return $cadena;
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
            $transaction = DB::transaction(function() use ($request){
                $arr = $request->except('_token');
                $arr['value'] = strtoupper($arr['value']);
                if(array_key_exists('lkp_city_id', $arr)){
                    $validation = Lookup::where('type',$arr['type'])->where('value',$arr['value'])->where('lkp_city_id',$arr['lkp_city_id'])->get();
                }else {
                    $validation = Lookup::where('type',$arr['type'])->where('value',$arr['value'])->get();
                }
                if(count($validation)==0){
                    $arr['key_value'] = $this->getKeyValue($arr['value']);
                    $lookup = Lookup::create($arr);
                    if ($lookup) {
                        if(array_key_exists('brands_ids', $arr)){
                          foreach ($request->brands_ids as $brand_id) {
                            LkpPartBrand::create(['lkp_part_id'=>$lookup->id,'brand_id'=>$brand_id]);
                          }
                        }
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
                    return redirect()->action('LookupController@index')->with($notification);
                }else{
                    $notification = array(
                      'message' => 'Value must be unique.',
                      'alert-type' => 'error'
                    );
                    return back()->with($notification)->withInput($request->all());
                }
            });

            return $transaction;
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';
            $message = $e->getMessage();
            $transaction = array(
                'message' => $cad,
                'alert-type' => 'error'
            );
        }
        return back()->with($transaction)->withInput($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Lookup::findOrFail($id);
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
        $types =  DB::table('lookups')->where('type','configuration')->where('band_add',1)->orderBy('value')->get();
        $lookup = Lookup::findOrFail($id);
        $cities = DB::table('lookups')->where('type','cities')->where('active',1)->orderBy('value')->get();    
        $brands = MachineBrand::where('lkp_type_id',54)->orderBy('brand')->orderBy('model')->get();
        $brands_on_part = DB::table('parts_lkp_brands')->where('lkp_part_id',$id)->get();
        $brands_ids = [];
        foreach ($brands_on_part as $brand_p )
            array_push($brands_ids,(string) $brand_p->brand_id);
        return view('lookups.edit',compact('types','lookup','cities','brands','brands_ids'));
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
            $transaction = DB::transaction(function() use ($request, $id){
                $arr = $request->except('_token','_method');
                $arr['value'] = strtoupper($arr['value']);
                if(array_key_exists('lkp_city_id', $arr))
                  $validation = Lookup::where('type',$arr['p_key_value'])->where('value',$arr['value'])->where('lkp_city_id',$arr['lkp_city_id'])->whereNotIn('id',[$id])->get();
                else{
                  $validation = Lookup::where('type',$arr['p_key_value'])->where('value',$arr['value'])->whereNotIn('id',[$id])->get();
                  $arr['lkp_city_id'] = null;
                }
                if(count($validation)==0){
                    $res = Lookup::findOrFail($id);
                    $key_value = $this->getKeyValue($arr['value']);
                    $res->update(['value'=>$arr['value'], 'key_value'=>$key_value, 'lkp_city_id'=>$arr['lkp_city_id'],'type'=>$arr['type']]);
                    $res->save();
                    if ($res) {
                        LkpPartBrand::where('lkp_part_id', $id)->delete();
                        if(array_key_exists('brands_ids', $arr) && $arr['type']=='part_type'){
                          foreach ($request->brands_ids as $brand_id) {
                            LkpPartBrand::create(['lkp_part_id'=>$id,'brand_id'=>$brand_id]);
                          }
                        }
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
                    return redirect()->action('LookupController@index')->with($notification);
                }else{
                    $notification = array(
                      'message' => 'Value must be unique.',
                      'alert-type' => 'error'
                    );
                    return back()->with($notification)->withInput($request->all());
                }
            });

            return $transaction;
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';
            $message = $e->getMessage();
            $transaction = array(
                'message' => $cad,
                'alert-type' => 'error'
            );
        }
        return back()->with($transaction)->withInput($request->all());
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
                $Lookup = Lookup::findOrFail($id);
                $Lookup->active = $Lookup->active == 0 ? 1 : 0;
                if($Lookup->save())
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
