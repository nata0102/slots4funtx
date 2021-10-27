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
        return view('percentage_price.index',compact('res'));  
    }

    public function searchWithFilters($params){
        $qry = "select p.*, m.game_title, l.value from percentage_price_machine p, machines m, lookups l
                where p.machine_id = m.id and p.lkp_type_id=l.id";
        if (array_key_exists('type', $params))
            $qry .= " and l.value like '%".$params['type']."%'";
        if (array_key_exists('machine', $params))
            $qry .= " and m.game_title like '%".$params['machine']."%'";
        $qry .= " order by m.game_title, l.value;";
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
        $machines = DB::table('machines')->whereNotIn('id', DB::table('percentage_price_machine')->pluck('machine_id')->toArray())->orderBy('game_title')->get();
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
        $machines = DB::table('machines')->whereNotIn('id', DB::table('percentage_price_machine')
            ->where('id','!=',$id)->pluck('machine_id')->toArray())->orderBy('game_title')->get();
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
