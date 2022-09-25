<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Charge;
use App\Models\Lookup;
use Auth;


class ChargesController extends Controller
{

    public function __construct(){

      if(!\Session::has('data')) \Session::put('data', array());

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $res = null;
        switch ($request->option) {
            case 'average':
                return $this->getAverage($request->all());
            break;
        }
        return $res;
    }

    public function getAverage($params){
        return Charge::where("machine_id", $params['machine_id'])->whereNotNull('utility_s4f')
                ->orderBy('id','desc')->take(5)->avg('utility_s4f');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data = \Session::get('data');
        if( url()->previous() != url()->current() ){
            session()->forget('urlBack');
            session(['urlBack' => url()->previous()]);
        }
        $machine_ctrl = new MachineController();
        $machines = $machine_ctrl->getMachinesFlatPercentage();
        $types = Lookup::where('type','charge_type')->orderBy('value')->get();
        return view('charges.create',compact('machines','types','data'));

    }

    public function storeData(Request $request){
        $data = \Session::get('data');
        $data[$request->machine_id] = $request->all();
        \Session::put('data', $data);
        return redirect()->action('ChargesController@create');
    }

    public function storeInitialNumbers(Request $request){
        try{
            $transaction = DB::transaction(function() use($request){
                $data = $request->all();
                $data['user_id'] = Auth::id();
                Charge::create($data);

                $notification = array(
                      'message' => 'Successful!!',
                      'alert-type' => 'success'
                );
                return $notification;
            });
            return redirect()->action('ChargesController@create')->with($transaction);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try{
            $transaction = DB::transaction(function() use($request){
                $rows = $request->all();
                foreach ($rows as $row){
                    $row['user_id'] = Auth::id();
                    if($row['utility_s4f'] != null && $row['payment_client'] != null){
                        $row['band_paid_out'] = 0;
                        if($row['utility_s4f'] == $row['payment_client'])
                            $row['band_paid_out'] = 1;
                    }
                    Charge::create($row);
                }
                $notification = array(
                      'message' => 'Successful!!',
                      'alert-type' => 'success'
                );
                return $notification;
            });
            return redirect()->action('ChargesController@index')->with($transaction);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
