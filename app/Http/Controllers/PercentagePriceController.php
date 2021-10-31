<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PercentagePrice;

class PercentagePriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $res = [];
        switch ($request->option) {
            default:
                $res = $this->searchWithFilters($request->all());
            break;
        }
        $types =  DB::table('lookups')->where('type','type_price')->get();

        return view('percentage_price.index',compact('res','types'));  
    }

    public function searchWithFilters($params){
        $qry = "select tab1.*,concat(tab1.machine_id,' - ',tab1.game,' - ',ifnull(tab1.serial,'')) as machine_name from (select p.*,mc.serial, (select value from lookups where id=p.lkp_type_id) as type,(select value from lookups where id=mc.lkp_game_id) as game from percentage_price_machine p, machines mc where mc.id=p.machine_id and mc.active =1) as tab1 ";
        if(count($params)){
            if($params['type'] != "" || $params['machine'] != "")
                $qry .= " where ";
            if($params['type'] != ""){
                if(substr($qry, -6) != "where ")
                    $qry.= " and ";
                $qry .= " tab1.lkp_type_id =".$params['type'];
            }
            if($params['machine'] != ""){
                if(substr($qry, -6) != "where ")
                    $qry.= " and ";
                $qry .= " concat(tab1.machine_id,' - ',tab1.game,' - ',ifnull(tab1.serial,'')) like '%".$params['machine']."%'";
            }
        }
        $qry .= " order by tab1.type, tab1.machine_id, tab1.game, tab1.serial;";
        return DB::select($qry);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types =  DB::table('lookups')->where('type','type_price')->get();
        $qry = "select m.*,l.value from machines m, lookups l where m.lkp_game_id=l.id and m.active = 1 and m.id not in (select machine_id from percentage_price_machine);";
        $machines = DB::select($qry);
        return view('percentage_price.create',compact('types','machines'));
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
            'machine_id' => 'required',
            'lkp_type_id' => 'required',
            'amount' => 'required',
        ]);    
        try{
            $transaction = DB::transaction(function() use($request){                             
                $arr = $request->except('_token');         
                $percentage_price = PercentagePrice::create($arr);
                if ($percentage_price) {
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
                $this->insertMachineHistory($request->machine_id);
                return $notification;
            });

            return redirect()->action('PercentagePriceController@index')->with($transaction);
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';          
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
        $percentage_price = PercentagePrice::findOrFail($id);
        $types =  DB::table('lookups')->where('type','type_price')->get();
        $qry = "select m.*,l.value from machines m, lookups l where m.lkp_game_id=l.id and m.active = 1 and m.id not in (select machine_id from percentage_price_machine where id != ".$id.");";
        $machines = DB::select($qry);
        /*$machines = DB::table('machines')->where('active',1)->whereNotIn('id', DB::table('percentage_price_machine')->where('id','!=',$id)->pluck('machine_id')->toArray())->orderBy('game_title')->get();*/
        return view('percentage_price.edit',compact('types','machines','percentage_price'));
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
            'machine_id' => 'required',
            'lkp_type_id' => 'required',
            'amount' => 'required',
        ]);    
        try{
            $transaction = DB::transaction(function() use($request, $id){                             
                $arr = $request->except('_token','_method');         
                $percentage_price = PercentagePrice::findOrFail($id);
                $percentage_price->update($arr);
                $percentage_price->save();
                if ($percentage_price) {
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
                $this->insertMachineHistory($request->machine_id);
                return $notification;
            });

            return redirect()->action('PercentagePriceController@index')->with($transaction);
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';          
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
        $transaction = DB::transaction(function() use($id){
            $percentage_price = PercentagePrice::findOrFail($id);
            $machine_id = $percentage_price->machine_id;
            $percentage_price->delete();
            $this->insertMachineHistory($machine_id);
            return response()->json(200);
        });
        return $transaction;
    }
}
