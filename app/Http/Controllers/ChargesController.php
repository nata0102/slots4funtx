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
                $res = $this->getList($request->all());
            break;
        }
        return view('charges.index',compact('res'));
    }

    public function getList($params){
        $qry = "select c.id,c.machine_id,c.utility_calc,c.utility_s4f,c.payment_client,c.band_paid_out,c.user_id,
            (select concat(m.serial,' - ',g.name)
            from machines m,game_catalog g where m.game_catalog_id = g.id and m.id=c.machine_id) as name_machine,date(c.created_at) as date,
            ifnull((select cl.name  from users u, clients cl where u.client_id=cl.id
            and u.id=c.user_id),'S4F') as client_name
            from charges c where c.type != 'initial_numbers' ";
        if (array_key_exists('date_ini', $params))
            if($params['date_ini'] != null)
                $qry.= " and date(created_at) >= '".$params['date_ini']."'";
        if (array_key_exists('date_fin', $params))
            if($params['date_fin'] != null)
                $qry.= " and date(created_at) <= '".$params['date_fin']."'";
        if (array_key_exists('band_paid_out', $params))
            if($params['band_paid_out'] != null)
                $qry.= " and band_paid_out = ".$params['band_paid_out'];
        $qry .= "  order by created_at desc limit 20;";
        return DB::select($qry);
    /*    $qry = "select date(created_at) as date_charge from charges group by date(created_at) order by created_at desc;";
        $res = DB::select($qry);
        foreach ($res as &$r) {
            $qry = "select *, (select concat(m.id,' - ',m.serial,' - ',g.name)
            from machines m,game_catalog g
            where m.game_catalog_id = g.id and m.id=c.machine_id) as name_machine
            from charges c where date(created_at) = '".$r->date_charge."' and c.type != 'initial_numbers';";
            $r->charges = DB::select($qry);
        }
        return $res;*/
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
        $data[] = $request->all();
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
                $res = \Session::get('data');
                $payment_client = (float)$request->total;
                foreach ($res as &$row){
                    $aux = [];
                    $aux['user_id'] = Auth::id();
                    $aux['band_paid_out'] = 0;
                    $aux['master_in'] = $row['masterIn'];
                    $aux['master_out'] = $row['masterOut'];
                    $aux['jackpot_out'] = $row['jackpotout'];
                    $aux['period_in'] = $row['periodIn'];
                    $aux['period_out'] = $row['periodOut'];
                    $aux['period_date'] = $row['date'];
                    $aux['machine_id'] = $row['machine_id'];
                    $aux['percentage'] = $row['percentage'];
                    $aux['utility_calc'] = $row['utility_calc'];
                    $aux['utility_s4f'] = $row['utility_s4f'];
                    $aux['type'] = $row['type'];
                    $aux['utility_calc'] = $row['utility_calc'];
                    $aux['utility_s4f'] = $row['utility_s4f'];

                    if($payment_client > 0){
                        $aux['payment_client'] = $payment_client;
                        $payment_client = $payment_client - $aux['utility_s4f'];
                        if($payment_client >= 0){
                            $aux['band_paid_out'] = 1;
                            $aux['payment_client'] = $aux['utility_s4f'];
                        }
                    }
                    Charge::create($aux);
                }
                $notification = array(
                      'message' => 'Successful!!',
                      'alert-type' => 'success'
                );
                \Session::forget('data');

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
