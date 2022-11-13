<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Address;
use Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class InvoiceController extends Controller
{
	public function index(Request $request){
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
        	$folio = $type."-".intval($arr[1])+1;
        }
        return $folio;
    }


   	public function createInvoiceDetails($charges_ids,$params,$type){
   		if(count($charges_ids) > 0){
            $arr_invoice = [];
            $arr_invoice['folio'] = $type."-".$this->getFolio($type);
            $arr_invoice['type'] = "charges";
            $arr_invoice['date_invoice'] = date('Y-m-d');
            $arr_invoice['discount'] = $params['discount'];
            $arr_invoice['total_system'] = $params['total'];
            $arr_invoice['total_discount'] = $params['total_discount'];
            $arr_invoice['user_id'] = Auth::id();
            $arr_invoice['client_id'] = $params['client_id'];
            $arr_invoice['payment_client'] = $params['payment_client'];
            $invoice = Invoice::create($arr_invoice);
            if($params['total_invoice_modified'] == $params['payment_client'])
            	$arr_invoice['band_paid_out'] = 1;
            foreach($charges_ids as $charge_id)
            	InvoiceDetail::create(['invoice_id'=>$invoice->id,'charge_id'=>$charge_id]);
        }
   	}

   	public function show($id){
   		$pdf = null;
   		$invoice = Invoice::with('client')->findOrFail($id);
   		$invoice->address = Address::with('city','county')->where('client_id',$invoice->client_id)->first();
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
   		$path_logo = public_path().'/images/logo-black.png';
        if ( !empty($path_logo) ){
            $headers = array('Content-Type' => 'application/octet-stream');
            $res['logo'] = Response::make( base64_encode( file_get_contents($path_logo) ), 200, $headers);
        }

   		//$res['details'] = InvoiceDetail::with('charges')->where('invoice_id',$invoice->id)->get();
   		$qry = "select c.*,
		(select concat(m.id,' - ',m.serial,' - ',g.name) from machines m,game_catalog g where m.game_catalog_id = g.id and m.id=c.machine_id) as name_machine 
		from invoices_details i,charges c where i.charge_id=c.id and invoice_id=".$invoice->id.";";
   		$res['details'] = DB::select($qry);
   		$view = view('pdfs.InvoiceCharges', compact('res'));         
        $view = preg_replace('/>\s+</', '><', $view);
        $pdf = \PDF::loadHTML($view);        
        return $pdf->stream();
   		//return $details;
   	}
}
