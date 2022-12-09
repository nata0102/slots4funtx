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
      if(!\Session::has('client_id'));
      if(!\Session::has('address_id'));
      if(!\Session::has('invoiceSelect'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        \Session::forget('data');
        \Session::forget('client_id');
        \Session::forget('address_id');
        \Session::forget('invoiceSelect');
        $res = null;
        $clients = null;
        switch ($request->option) {
            case 'average':
                return $this->getAverage($request->all());
            break;
            default:
                $res = $this->getList($request->all());
                $clients = $this->getClientsWithCharges();
            break;
        }
        return view('charges.index',compact('res','clients'));
    }


    public function updateInvoice($key)
    {
      $dt = \Session::get('data');
      $data = $dt[$key];

      if($data['band_invoice'] == 1){
        $data['band_invoice'] = 0;
      }
      else {
        $data['band_invoice'] = 1;
      }

      $dt[$key] = $data;

      \Session::put('data', $dt);

      return response()->json('200');
    }


    public function getList($params){
        $role = Auth::user()->role->key_value;
        $user_id = Auth::id();
        $qry = "select date(created_at) as date_charge,
                group_concat(c.id) as charges_ids
                from charges c where type!='initial_numbers' ";
        switch($role){
            case 'employee': $qry .= " and c.user_id = " . $user_id;  break;
            case 'client': break;
        };
        if (array_key_exists('date_ini', $params) && $params['date_ini'] != null)
            $qry .= " and date(c.created_at) >= '".$params['date_ini']."'";
        if (array_key_exists('date_fin', $params) && $params['date_fin'] != null)
            $qry .= " and date(c.created_at) <= '".$params['date_fin']."'";

        if (array_key_exists('clients_ids', $params)){
            if($params['clients_ids'] != "" && $params['clients_ids'] != null){
                if(!in_array("all", $params['clients_ids']))
                    $qry .= " and c.machine_id in (select id from machines where address_id in (".implode(',',$params['clients_ids']).")) ";
            }
        }
        $qry .= " group by date(created_at) order by date(created_at) desc ";
        if (!array_key_exists('date_ini', $params) && !array_key_exists('date_fin', $params))
            $qry .= " limit 10";
        $aux = DB::select($qry);

        $res = [];
        foreach ($aux as &$a){
            $a = $this->getListInvoicesAndCharges($a, $params);
            if(count($a->invoices) > 0 || count($a->charges) > 0)
                array_push($res, $a);
        }
        return $res;
    }

    public function getListInvoicesAndCharges($row,$params){
        $qry = "select invoice_id, (select folio from invoices where id=i.invoice_id) as folio,
        (select band_cancel from invoices where id=i.invoice_id) as band_cancel,
        (select total_discount - payment_client from invoices where id=i.invoice_id) as total_due,
        (select if(band_paid_out,'#B1FEAB', '#FEB4AB') from invoices where id=i.invoice_id) as row_color,
        (select c.name from clients c, invoices inv where inv.id=i.invoice_id and c.id=inv.client_id) as client_name,
        (select a.business_name from addresses a, invoices inv where inv.id=i.invoice_id and a.id=inv.address_id) as business_name
        from invoices_details i where charge_id in (".$row->charges_ids.") ";
        if (array_key_exists('band_paid_out', $params) && $params['band_paid_out'] != -1){
            if($params['band_paid_out'] == 2) // Cancelada
                $qry .= " and i.invoice_id in (select id from invoices where band_cancel = 1) ";
            else
                $qry .= " and i.invoice_id in (select id from invoices where band_paid_out = ".$params['band_paid_out']." and band_cancel = 0) ";
        }
        $qry .= " group by invoice_id;";
        $rows = DB::select($qry);
        $row->invoices = [];
        if(count($rows)>0)
            $row->invoices = $rows;

        $row->charges = [];
        if (array_key_exists('band_paid_out', $params) && $params['band_paid_out'] == 2)
            return $row;

        $qry = "select *,if(t.band_paid_out = 1, '#B1FEAB', '#FEB4AB') as row_color
            from (select c.id,c.machine_id,c.utility_calc,c.utility_s4f,c.payment_client,c.band_paid_out,c.user_id,
            (select concat(m.serial,' - ',g.name) from machines m,game_catalog g where m.game_catalog_id = g.id and m.id=c.machine_id) as name_machine,
            date(c.created_at) as date,
            (select ifnull(name,'S4F')  from users where id=c.user_id) as user_add,
            (select concat(cl.name,' - ',a.business_name) from addresses a, clients cl, machines m
            where a.client_id = cl.id and a.id=m.address_id and m.id=c.machine_id) as client_business
            from charges c where id in (".$row->charges_ids.") and id not in (select inv_d.charge_id from invoices_details inv_d, invoices i where inv_d.invoice_id=i.id and i.band_cancel = 0 and inv_d.charge_id is not null)";
        if (array_key_exists('band_paid_out', $params) && $params['band_paid_out'] != -1)
            $qry.= " and c.band_paid_out = ".$params['band_paid_out'];
        $qry .= ") as t;";
        $rows = DB::select($qry);
        if(count($rows)>0)
            $row->charges = $rows;
        return $row;
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
        //$data = \Session::forget('data');
        $data = \Session::get('data');
        $client_id = \Session::get('client_id');
        $address_id = \Session::get('address_id');
        $invoiceSelect = \Session::get('invoiceSelect');
        if(count($data) == 0){
          \Session::forget('client_id');
          \Session::forget('address_id');
          \Session::forget('invoiceSelect');
        }

        if( url()->previous() != url()->current() ){
            session()->forget('urlBack');
            session(['urlBack' => url()->previous()]);
        }
        $machines = [];
        $clients = $this->getClientsWithMachines();
        $types = Lookup::where('type','charge_type')->orderBy('value','desc')->get();
        $machinesid = "";
        $c = 0;
        foreach ($data as $key => $value) {
          $machinesid .= $value['machine_id'];
          $c++;
          if($c<count($data))
            $machinesid .=",";
        }
        return view('charges.create',compact('machinesid','machines','types','data','clients','client_id','invoiceSelect','address_id'));

    }

    public function getClientsWithCharges(){
        $qry = "select * from(
                select a.id, concat(c.name,' - ', a.business_name) as name
                from clients c, addresses a, machines m
                where c.id = a.client_id and a.active = 1 and c.active = 1
                and m.active=1 and m.address_id = a.id and m.id in (select machine_id from charges)
                ) as t group by t.id,t.name order by t.name;";
        return DB::select($qry);
    }

    public function getClientsWithMachines(){
        $machine_ctrl = new MachineController();
        $qry = "select * from (
                select c.id,c.name, a.id as address_id,a.business_name,
                (select count(*) from machines where address_id=a.id and active=1 and id in (select machine_id from percentage_price_machine where lkp_type_id = 45)) as total
                from clients c, addresses a where c.id = a.client_id and a.active = 1 and c.active = 1) as t where t.total > 0 order by t.name, t.business_name;";
        $res = DB::select($qry);
        foreach ($res as &$r)
            $r->machines = $machine_ctrl->getMachinesFlatPercentage($r->address_id);
        return $res;
    }

    public function deleteData($key)
    {
      $dt = \Session::get('data');
      unset($dt[$key]);
      \Session::put('data', $dt);

      if(count($dt) == 0){
        \Session::forget('client_id');
        \Session::forget('address_id');
        \Session::forget('invoiceSelect');
      }
      return back();
    }

    public function storeData(Request $request){
      //dd($request);
        $data = \Session::get('data');

        if(count($data) == 0){
          $client = \Session::get('client_id');
          \Session::put('client_id', $request->client);

          $address = \Session::get('address_id');
          \Session::put('address_id', $request->address_id);

          $invoiceSelect = \Session::get('invoiceSelect');
          \Session::put('invoiceSelect', $request->invoice_select);
        }

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
                $payment_client = (float)$request->payment_client;
                $charges_ids = [];
                $client_id = null;
                $params = $request->all();
                $invoice_ctrl = new InvoiceController();

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
                    $client_id = $row['client'];

                    if($payment_client > 0){
                        $aux['payment_client'] = $payment_client;
                        $payment_client = $payment_client - $aux['utility_s4f'];
                        if($payment_client >= 0){
                            $aux['band_paid_out'] = 1;
                            $aux['payment_client'] = $aux['utility_s4f'];
                        }
                    }
                    $charge = Charge::create($aux);
                    if($params['type_invoice'] == "with_invoice")
                        array_push($charges_ids, $charge->id);
                }
                if(count($charges_ids)){
                  $params['client_id'] = $client_id;
                  $params['type'] = 'charges';
                  $invoice = $invoice_ctrl->createInvoice($params);
                  $invoice_ctrl->createDetailsCharges($charges_ids, $invoice->id);
                }

                $notification = array(
                      'message' => 'Successful!!',
                      'alert-type' => 'success'
                );
                \Session::forget('data');
                \Session::forget('client');

                return $notification;
            });
            return redirect()->action('ChargesController@index')->with($transaction);
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.'.$e->getMessage();
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
        $qry = "select c.*,
        (select format(avg(utility_s4f),2) from charges where machine_id =m.id and utility_s4f is not null order by id desc limit 5) as average,
        (select value from lookups where id=m.lkp_owner_id) as owner,
        (select concat(cl.name,' - ',a.business_name) from addresses a, clients cl
        where a.client_id = cl.id and a.id=m.address_id) as client_business,
        (select name from game_catalog where id=m.game_catalog_id) as game
        from machines m, charges c where m.id=c.machine_id and c.id=".$id.";";
        $charge = DB::select($qry)[0];
        return view('charges.edit',compact('charge'));
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
            $transaction = DB::transaction(function() use($request,$id){
                $charge = Charge::findOrFail($id);
                $params = $request->only('utility_s4f','utility_calc','payment_client');
                if($params['utility_s4f']==$params['payment_client'])
                    $params['band_paid_out'] = 1;
                $charge->update($params);
                $charge->save();
                if ($charge) {
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
            return redirect()->action('ChargesController@index')->with($transaction);
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.:';
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
        //
    }
}
