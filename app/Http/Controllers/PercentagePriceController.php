<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PercentagePrice;
use App\Models\Machine;


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
        $periodicities =  DB::table('lookups')->where('type','payment_periodicity')->where('active',1)->orderBy('value')->get();

        return view('percentage_price.index',compact('res','types','periodicities'));
    }

    public function searchWithFilters($params){
        $qry = "select tab1.*,concat(tab1.machine_id,' - ',owner,' - ',game,' - ',ifnull(tab1.serial,'')) as machine_name from
          (select p.*,mc.serial, (select value from lookups where id=p.lkp_type_id) as type,
          (select value from lookups where id=mc.lkp_owner_id) as owner,
          (select name from game_catalog where id=mc.game_catalog_id) as game,
          (select value from lookups where id=p.lkp_periodicity_id) as type_periodicity
          from percentage_price_machine p, machines mc
          where mc.id=p.machine_id and mc.active =1) as tab1 ";
        if(count($params)){
            if($params['type'] != "" || $params['machine'] != "" ||  $params['periodicity'] != "" )
                $qry .= " where ";
            if($params['type'] != ""){
                if(substr($qry, -6) != "where ")
                    $qry.= " and ";
                $qry .= " tab1.lkp_type_id =".$params['type'];
            }
            if($params['machine'] != ""){
                if(substr($qry, -6) != "where ")
                    $qry.= " and ";
                $qry .= " concat(tab1.machine_id,' - ',ifnull(tab1.serial,'')) like '%".$params['machine']."%'";
            }
            if($params['periodicity'] != ""){
                if(substr($qry, -6) != "where ")
                    $qry.= " and ";
                $qry .= " tab1.lkp_periodicity_id =".$params['periodicity'];
            }
        }
        $qry .= " order by tab1.type, tab1.machine_id, tab1.serial;";
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
        $types =  DB::table('lookups')->where('type','type_price')->get();
        $payments =  DB::table('lookups')->where('type','payment_periodicity')->where('active',1)->orderBy('value')->get();
        $qry = "select m.*,(select value from lookups where id=m.lkp_owner_id) as owner,
        (select name from game_catalog where id=m.game_catalog_id) as game
        from machines m where m.active = 1 and m.id not in (select machine_id from percentage_price_machine);";
        $machines = DB::select($qry);
        return view('percentage_price.create',compact('types','machines','payments'));
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
            'lkp_periodicity_id' => 'required',
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
      if( url()->previous() != url()->current() ){
        session()->forget('urlBack');
        session(['urlBack' => url()->previous()]);
      }
        $percentage_price = PercentagePrice::findOrFail($id);
        $types =  DB::table('lookups')->where('type','type_price')->get();
        $payments =  DB::table('lookups')->where('type','payment_periodicity')->where('active',1)->orderBy('value')->get();
        $machine = Machine::with('owner','game')->findOrFail($percentage_price->machine_id);
        return view('percentage_price.edit',compact('types','machine','percentage_price','payments'));
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
            'lkp_periodicity_id' => 'required',
            'amount' => 'required',
        ]);
        try{
            $transaction = DB::transaction(function() use($request, $id){
                $arr = $request->except('_token','_method');
                $percentage_price = PercentagePrice::findOrFail($id);
                if($arr['lkp_periodicity_id'] != 68)
                  $arr['payday'] = null;
                $percentage_price->update($arr);
                $percentage_price->save();
                $this->insertMachineHistory($percentage_price->machine_id);
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
                return $notification;
            });

            return redirect()->action('PercentagePriceController@index')->with($transaction);
        }catch(\Exception $e){return $e->getMessage();
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
