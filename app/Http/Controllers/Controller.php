<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\MachineHistory;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function saveGetNameImage($image, $path){
    	$key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($pattern)-1;
        for($i=0;$i < 10;$i++)
            $key .= $pattern{mt_rand(0,$max)};
        $key = $key.strtotime(date('Y-m-d H:i:s'));

        $fileName = $key.'.'.$image->getClientOriginalExtension();
        $image->move(public_path().$path, $fileName);
        return $fileName;
    }

    public function insertMachineHistory($machine){
        ////FALTA type_price Y type_price_amount Y active    y el owner   
        $band = false;
        $history = MachineHistory::where('machine_id',$machine->id)->orderBy('id','desc')->first();
        if($history == null)
            $band = true;
        else{
            if($machine->address_id != $history->address_id || $machine->lkp_status_id != $history->lkp_status_id || $machine->machine_sold_id != $history->machine_sold_id /*||
                $machine->active != $history->active*/)
                $band = true;
        }
        if($band){
            $arr_history = ['machine_id' => $machine->id,'address_id'=>$machine->address_id,
                'lkp_status_id'=>$machine->lkp_status_id,'machine_sold_id' => $machine->machine_sold_id,'type_price'=> null,'type_price_amount'=>null,'created_at'=>date('Y-m-d H:i:s')];
            MachineHistory::create($arr_history);
        }
    }

}
