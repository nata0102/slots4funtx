<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Address;
use App\Models\Lookup;
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
			break;
		}
		return view('invoices.index',compact('res'));
	}

	public function getList($params){
		$qry = "select i.*,
		(select value from lookups where type='invoices_type' and key_value=i.type limit 1) as type_value,
		(select name from clients where id=i.client_id) as client_name
		from invoices i;";
		return DB::select($qry);
	}

    public function getFolio($type){
    	$invoice = Invoice::where('folio', 'like', '%'.$type.'%')->orderBy('id','desc')->first();
        $folio = "0001";
        if($invoice != null){
        	$arr = explode('-', $invoice->folio);
        	$num = intval($arr[1])+1;
        	$folio = $type."-000".$num;
        }
        return $folio;
    }


   	public function createInvoiceDetails($charges_ids,$params,$type){
   		if(count($charges_ids) > 0){
            $arr_invoice = [];
            $arr_invoice['folio'] = $this->getFolio($type);
            $arr_invoice['type'] = "charges";
            $arr_invoice['date_invoice'] = date('Y-m-d');
            $arr_invoice['discount'] = $params['discount'];
            $arr_invoice['total_system'] = $params['total_invoice'];
            $arr_invoice['total_discount'] = $params['total_invoice_modified'];
            $arr_invoice['user_id'] = Auth::id();
            $arr_invoice['client_id'] = $params['client_id'];
            $arr_invoice['payment_client'] = $params['payment_client'];
            $arr_invoice['address_id'] = $params['client_address_id'];
            if($params['total_invoice_modified'] == $params['payment_client'])
            	$arr_invoice['band_paid_out'] = 1;
            $invoice = Invoice::create($arr_invoice);
            foreach($charges_ids as $charge_id)
            	InvoiceDetail::create(['invoice_id'=>$invoice->id,'charge_id'=>$charge_id]);
        }
   	}

   	public function create()
    {
        if( url()->previous() != url()->current() ){
            session()->forget('urlBack');
            session(['urlBack' => url()->previous()]);
        }

				$data = \Session::get('data');
        $charges_ctrl = new ChargesController();
        $clients = $charges_ctrl->getClientsWithMachines();
        $types = Lookup::where('type','invoices_type')->orderBy('value','desc')->get();

				if(count($data)==0)
					$machines = array();
				else
					$machines = $data['machines'];

				 \Session::forget('data');

        return view('invoices.create',compact('types','clients','machines','data'));
    }

		public function machines(Request $request){
			$qry = "select *,
			(select concat(m.id,' - ',m.serial,' - ',g.name)
			from machines m,game_catalog g
			where m.game_catalog_id = g.id and m.id=c.machine_id) as name_machine
			from charges c
			where c.id not in (select charge_id from invoices_details ind, invoices i
			where ind.invoice_id = i.id and i.band_cancel is false)
			and c.type != 'initial_numbers' and c.machine_id in
			(select id from machines where address_id = 1)
			and date(created_at)>='".$request->from."' and date(created_at)<='".$request->to."';";
			$machines = DB::select($qry);
			$data['type'] = $request->type;
			$data['client'] = $request->client;
			$data['to'] = $request->to;
			$data['from'] = $request->from;
			$data['machines'] = $machines;
			\Session::put('data', $data);
			return redirect()->action('InvoiceController@create');

		}

   	public function show($id){
   		$pdf = null;
   		$invoice = Invoice::with('client','address')->findOrFail($id);

   		switch ($invoice->type) {
   			case 'charges':
   				$pdf = $this->getChargesPDF($invoice);
   				break;

   			default:
   				# code...
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
}
