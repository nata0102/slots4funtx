<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Address;
use App\Models\Lookup;
use App\Models\InvoicePayment;
use Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class InvoiceController extends Controller
{
	public function __construct(){
		if(!\Session::has('data')) \Session::put('data', array());
	}

	public function index(Request $request){
		\Session::forget('data');
		$res = [];
		switch($request->option){
			default:
				$res = $this->getList($request->all());
        $typesClients = $this->getTypesClients();
			break;
		}
		return view('invoices.index',compact('res','typesClients'));
	}

  public function getTypesClients(){
      $res_final = [];
      $types = Lookup::where('type','invoices_type')->get();
      foreach ($types as &$type) {
        $qry = "select id, business_name, (select name from clients where id=a.client_id) as client_name
        from addresses a where id in (select address_id from invoices where type='".$type->key_value."')";
        $type->clients = DB::select($qry);
        if(count($type->clients) > 0)
          array_push($res_final,$type);
      }
      return $res_final;
  }

	public function getList($params){
    $role = Auth::user()->role->key_value;
    $user_id = Auth::id();

		$qry = "select i.*,a.business_name,(i.total_discount-i.payment_client) as debit,
    if(band_cancel,'#C4C4C1', if(band_paid_out,'#B1FEAB', '#FEB4AB')) as row_color,
		(select value from lookups where type='invoices_type' and key_value=i.type limit 1) as type_value,
		(select name from clients where id=i.client_id) as client_name
		from invoices i, addresses a where a.id=i.address_id ";
    switch($role){
      case 'employee': $qry .= " and i.user_id = " . $user_id;  break;
      case 'client': break;
    }
    if (array_key_exists('band_paid_out', $params) && $params['band_paid_out'] != -1){
      if($params['band_paid_out'] == 2) // Cancelada
        $qry .= " and band_cancel = 1 ";
      else
        $qry .= " and band_paid_out = ".$params['band_paid_out']." and band_cancel = 0 ";
    }
    if (array_key_exists('date_ini', $params) && $params['date_ini'] != null)
      $qry .= " and date(i.created_at) >= '".$params['date_ini']."'";
    if (array_key_exists('date_fin', $params) && $params['date_fin'] != null)
      $qry .= " and date(i.created_at) <= '".$params['date_fin']."'";
    if (array_key_exists('type', $params) && $params['type'] != null && $params['type'] != "all")
      $qry .= " and i.type = '".$params['type']."'";
    if (array_key_exists('clients_ids', $params) && $params['clients_ids'] != null && $params['clients_ids'] != ""){
      if(!in_array("all", $params['clients_ids']))
        $qry .= " and i.address_id in (".implode(',',$params['clients_ids']).") ";
    }

		return DB::select($qry);
	}

    public function getFolio(){
    	$invoice = Invoice::orderBy('id','desc')->first();

      $folio = "0001";
      if($invoice != null){
       	$num = intval($invoice->folio)+1;
       	$folio = "000".$num;
      }
      return $folio;
    }

    public function createInvoice($params){
      $arr_invoice = [];
      $arr_invoice['folio'] = $this->getFolio();
      $arr_invoice['type'] = $params['type'];
      switch ($params['type']) {
        case 'charges':
          $arr_invoice['client_id'] = $params['client_id'];
        break;        
        case 'flat_rate_invoice':
          $arr_invoice['client_id'] = $params['client_flat_rate'];
        break;
      }
      $arr_invoice['date_invoice'] = date('Y-m-d');
      $arr_invoice['discount'] = $params['discount'];
      $arr_invoice['total_system'] = $params['total_invoice'];
      $arr_invoice['total_discount'] = $params['total_invoice_modified'];
      $arr_invoice['user_id'] = Auth::id();
      $arr_invoice['payment_client'] = $params['payment_client'];
      $arr_invoice['address_id'] = $params['client_address_id'];
      if($params['total_invoice_modified'] == $params['payment_client'])
        $arr_invoice['band_paid_out'] = 1;
      return Invoice::create($arr_invoice);
    }

   	public function createDetailsCharges($charges_ids,$invoice_id){
        foreach($charges_ids as $charge_id)
         	InvoiceDetail::create(['invoice_id'=>$invoice_id,'charge_id'=>$charge_id]);
   	}

    public function createDetailsFlatRate($machines_ids, $invoice_id){
      $cad_machines = implode(',', $machines_ids);
      $qry = "select * from percentage_price_machine where machine_id in (".$cad_machines.");";
      $rows = DB::select($qry);
      foreach ($rows as $row) 
        InvoiceDetail::create(['invoice_id'=>$invoice_id,'machine_id'=>$row->machine_id, 'amount'=>$row->amount]);
    }

   	public function store(Request $request){  	
   		try{
            $transaction = DB::transaction(function() use($request){
                $data = $request->all();
                $invoice = $this->createInvoice($data);
                switch ($data['type']) {       
                	case 'charges':
                		$this->createDetailsCharges($request->charges_ids, $invoice->id);
                	break;
                  case 'flat_rate_invoice':
                    $this->createDetailsFlatRate($request->machines_ids, $invoice->id);
                  break;
                }

                $notification = array(
                      'message' => 'Successful!!',
                      'alert-type' => 'success'
                );
                return $notification;
            });
            return redirect()->action('InvoiceController@index')->with($transaction);
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';
            $transaction = array(
                'message' => $cad,
                'alert-type' => 'error'
            );
        }
   	}

   	public function  getClientsWithChargesNotInvoice(){
        $qry = "select * from (select  a.id as address_id,a.business_name,a.client_id as id,
		(select name from clients where id=a.client_id) as name,
		(select active from clients where id=a.client_id) as client_active
		from addresses a where a.active=1 and a.id in 
		(
			select m.address_id
			from charges ch,machines m
			where ch.machine_id=m.id and ch.type != 'initial_numbers'
			and ch.id not in (select charge_id from invoices_details where invoice_id in 
			(select id from invoices where band_cancel is false))
		)) as t1 where t1.client_active=1";
        return DB::select($qry);
    }

    public function getClientsWithFlatRate(){
      $res = [];
      $lookup = Lookup::where('type','type_price')->where('key_value','flat_rate')->first();
      if($lookup != null){
        $qry = "select * from (
        select  a.id as address_id,a.business_name,a.client_id as id,
        (select name from clients where id=a.client_id) as name,
        (select active from clients where id=a.client_id) as client_active
        from addresses a where a.active=1 and a.id in 
          (select m.address_id 
          from machines m, percentage_price_machine p
          where m.id = p.machine_id and p.lkp_type_id = ".$lookup->id.")
        ) as t1 where t1.client_active=1;";
        $res = DB::select($qry);
      }
      return $res;
    }

    public function getMachinesWithFlatRate(){
      $res = [];
      $lookup = Lookup::where('type','type_price')->where('key_value','flat_rate')->first();
      if($lookup != null){
        $qry = "select m.id,m.serial,m.address_id,
        (select name from game_catalog where id=m.game_catalog_id) as name_game,
        (select value from lookups where id=p.lkp_periodicity_id) as periodicity,
        p.amount from machines m, percentage_price_machine p
        where m.id = p.machine_id and p.lkp_type_id = ".$lookup->id."  order by m.address_id;";
        $res = DB::select($qry);
      }
      return $res;
    }

   	public function create()
    {
      if( url()->previous() != url()->current() ){
        session()->forget('urlBack');
        session(['urlBack' => url()->previous()]);
      }

		  $data = \Session::get('data');
      $clients = $this->getClientsWithChargesNotInvoice();
      $types = Lookup::where('type','invoices_type')->orderBy('value')->get();
      $clients_flat_rate = $this->getClientsWithFlatRate();
	 	  if(count($data)==0)
			 $machines = array();
		  else
			 $machines = $data['machines'];
		  \Session::forget('data');
      $machines_flat_rate = $this->getMachinesWithFlatRate();
      return view('invoices.create',compact('types','clients','machines','data','clients_flat_rate','machines_flat_rate'));
    }

	public function machines(Request $request){
		$qry = "select m.address_id,
			concat(m.id,' - ',m.serial,' - ', (select g.name from game_catalog g where id = m.game_catalog_id)) as name_machine, ch.id, date(ch.created_at) as date_charge,ch.utility_calc,ch.utility_s4f 
			from charges ch,machines m
			where ch.machine_id=m.id and ch.type != 'initial_numbers'
			and ch.id not in (select charge_id from invoices_details where invoice_id in 
			(select id from invoices where band_cancel is false)) and m.address_id = ".$request->client_address_id;
    if (array_key_exists('from', $request->all()) && $request->all()['from'] != "")
      $qry .= " and date(ch.created_at)>='".$request->from."' ";
    if (array_key_exists('to', $request->all()) && $request->all()['to'] != "")
      $qry .= " and date(ch.created_at)<='".$request->to."'";
		$machines = DB::select($qry);
		$data['type'] = $request->type;
		$data['client'] = $request->client;
		$data['to'] = $request->to;
		$data['from'] = $request->from;
		$data['machines'] = $machines;
		\Session::put('data', $data);
		return response (200);
	}

   	public function show($id){
   		$pdf = null;
   		$invoice = Invoice::with('client','address')->findOrFail($id);

   		switch ($invoice->type) {
   			case 'charges':
   				$pdf = $this->getChargesPDF($invoice);
   				break;
   		}
   		return $pdf;
   	}

   	public function destroy($id) {
   		$invoice = Invoice::findOrFail($id);
   		$invoice->band_cancel = 1;
   		$invoice->save();
   		return $invoice;
    }

   	public function getChargesPDF($invoice){
   		$res = [];

   		$res['logo'] = null;
   		$res['invoice'] = $invoice;
   		/*$path_logo = public_path().'/images/logo-black.png';
   		$path_logo_cancelled = public_path().'/images/cancelled.jpg';
		$headers = array('Content-Type' => 'application/octet-stream');

        if ( !empty($path_logo) )
            $res['logo'] = Response::make( base64_encode( file_get_contents($path_logo) ), 200, $headers);
         if ( !empty($path_logo_cancelled) )
            $res['logo_cancelled'] = Response::make( base64_encode( file_get_contents($path_logo_cancelled) ), 200, $headers);*/

   		$qry = "select c.*,
		(select concat(m.id,' - ',m.serial,' - ',g.name) from machines m,game_catalog g where m.game_catalog_id = g.id and m.id=c.machine_id) as name_machine
		from invoices_details i,charges c where i.charge_id=c.id and invoice_id=".$invoice->id.";";
   		$res['details'] = DB::select($qry);
   		$view = view('pdfs.InvoiceCharges', compact('res'));
        $view = preg_replace('/>\s+</', '><', $view);
        $pdf = \PDF::loadHTML($view);
        return $pdf->stream();
   	}

    public function edit($id)
    {
      if( url()->previous() != url()->current() ){
        session()->forget('urlBack');
        session(['urlBack' => url()->previous()]);
      }
      $types = Lookup::where('type','invoices_types_payments')->orderBy('value','desc')->get();
      $invoice = Invoice::with('client','address')->findOrFail($id);
      $qry = "select *,(select value from lookups where id = ip.lkp_type_id) as type
              from invoices_payments ip where invoice_id = ".$id.";";
      $payments = DB::select($qry);
      return view('invoices.edit',compact('types','invoice','payments'));
    }

    public function update(Request $request, $id)
    {
      //return $request->all();
      try{
        $transaction = DB::transaction(function() use($request, $id){
            $params = $request->all();          
            $sum_payment_client = 0;
            $invoice = Invoice::findOrFail($id);
            InvoicePayment::where('invoice_id',$id)->delete();

            if (array_key_exists('tab_type', $params)) {
              for($i =0; $i < count($params['tab_type']); $i++){
                $arr = [];
                $arr['invoice_id'] = $id;
                /*if($params['tab_id'][$i] != null)
                  $arr['id'] = $params['tab_id'][$i];*/
                $arr['lkp_type_id'] = $params['tab_type'][$i];
                if($params['tab_description'][$i] != null)
                  $arr['description'] = $params['tab_description'][$i];
                $arr['amount'] = $params['tab_amount'][$i];
                $sum_payment_client += $arr['amount'];
                InvoicePayment::create($arr);
              }
            }            
            if($params['payment_client'] == 0)
              $invoice->band_paid_out = 1;
            else
              $invoice->band_paid_out = 0;
            $invoice->payment_client = $sum_payment_client;
            $invoice->save();
            $notification = array(
                  'message' => 'Successful!!',
                  'alert-type' => 'success'
            );
            return $notification;
        }); 
        return redirect()->action('InvoiceController@index')->with($transaction);
      }catch(\Exception $e){
          $cad = 'Oops! there was an error, please try again later.';            
          $transaction = array(
              'message' => $cad. $e->getMessage(),
              'alert-type' => 'error' 
          );
      }

      return back()->with($transaction)->withInput($request->all());           
    }
}