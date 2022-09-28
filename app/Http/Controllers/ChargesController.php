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
            default:
                $res = $this->getList();
            break;
        }
        return $res;
    }

    public function getList(){
        $qry = "select date(created_at) as date_charge from charges group by date(created_at) order by created_at desc;";
        $res = DB::select($qry);
        foreach ($res as &$r) {
            $qry = "select *, (select concat(m.id,' - ',m.serial,' - ',g.name) 
            from machines m,game_catalog g 
            where m.game_catalog_id = g.id and m.id=c.machine_id) as name_machine
            from charges c where date(created_at) = '".$r->date_charge."' and c.type != 'initial_numbers';";
            $r->charges = DB::select($qry);
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

    public function deleteData($key)
    {
      $dt = \Session::get('data');
      unset($dt[$key]);
      \Session::put('data', $dt);

      return back();
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
    {return \Session::get('data');
      try{
            $transaction = DB::transaction(function() use($request){
                $res = $request->all();
                print_r($res); 
                abort(503,"prueba");
                return $res;
                $payment_client = $res->payment_client;
                foreach ($res->rows as &$row){
                    $row['user_id'] = Auth::id();
                    $row['band_paid_out'] = 0;
                    if($payment_client > 0){
                        $row['payment_client'] = $payment_client;
                        $payment_client = $payment_client - $row['utility_s4f'];
                        if($payment_client >= 0){
                            $row['band_paid_out'] = 1;
                            $row['payment_client'] = $row['utility_s4f'];
                        }
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
