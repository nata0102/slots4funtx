<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\MachineHistory;
use App\Models\PartHistory;
use App\Models\Part;
use App\Models\Lookup;
use App\Models\MachineBrand;
use App\Models\Permission;
use App\Models\Machine;
use Auth;
use Excel;


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

    public function insertMachineHistory($id){
        date_default_timezone_set('America/Chicago');
        $machine = Machine::with('owner')->findOrFail($id);
        $price_machine = DB::table('percentage_price_machine')->join('lookups', 'percentage_price_machine.lkp_type_id', '=', 'lookups.id')
        ->join('lookups as l2', 'percentage_price_machine.lkp_periodicity_id', '=', 'l2.id')
        ->where('machine_id',$machine->id)->select('percentage_price_machine.*','lookups.value', 'l2.value as periodicity')->first();
        $machine->type_price = null;
        $machine->type_price_amount = null;
        $machine->periodicity = null;
        $machine->payday = null;
        if($price_machine != null){
            $machine->type_price = $price_machine->value;
            $machine->type_price_amount = $price_machine->amount;
            $machine->payday = $price_machine->payday;
            $machine->periodicity = $price_machine->periodicity;
        }
        $machine->p_state_number = null;
        $machine->p_state_year = null;
        $machine->p_city_number = null;
        $machine->p_city_year = null;
        $city_permit = Permission::where('machine_id',$id)->where('lkp_type_permit_id',42)->first();
        if($city_permit != null){
            $machine->p_city_number = $city_permit->permit_number;
            $machine->p_city_year = $city_permit->year_permit;
        }
        $state_permit = Permission::where('machine_id',$id)->where('lkp_type_permit_id',41)->first();
        if($state_permit != null){
            $machine->p_state_number = $state_permit->permit_number;
            $machine->p_state_year = $state_permit->year_permit;
        }

        $band = false;
        $history = MachineHistory::where('machine_id',$machine->id)->orderBy('id','desc')->first();
        if($history == null)
            $band = true;
        else{
            if($machine->address_id != $history->address_id || $machine->lkp_status_id != $history->lkp_status_id || $machine->machine_sold_id != $history->machine_sold_id ||
                $machine->active != $history->active || $machine->owner->value != $history->owner_type ||
                $history->type_price != $machine->type_price || $history->type_price_amount != $machine->type_price_amount || $history->brand_id != $machine->machine_brand_id || $history->payday != $machine->payday || $history->periodicity != $machine->periodicity || $machine->p_city_number != $history->p_city_number ||
                $machine->p_city_year != $history->p_city_year || $machine->p_state_number != $history->p_state_number ||  $machine->p_state_year != $history->p_state_year)
                $band = true;
        }
        if($band){
            $arr_history = ['machine_id' => $machine->id,'address_id'=>$machine->address_id,
                'lkp_status_id'=>$machine->lkp_status_id,'machine_sold_id' => $machine->machine_sold_id,
                'active'=>$machine->active,'owner_type'=>$machine->owner->value,'created_at'=>date('Y-m-d H:i:s'), 'type_price' => $machine->type_price, 'type_price_amount' => $machine->type_price_amount, 'brand_id' => $machine->machine_brand_id, 'payday' => $machine->payday, 'periodicity' => $machine->periodicity,
                'p_state_number'=>$machine->p_state_number, 'p_state_year'=>$machine->p_state_year,
                'p_city_number'=>$machine->p_city_number,'p_city_year'=> $machine->p_city_year,
                'user_id' => Auth::id()];
            MachineHistory::create($arr_history);
        }
    }

    public function insertPartHistory($id){
      date_default_timezone_set('America/Chicago');
      $part = Part::findOrFail($id);
      $history = PartHistory::where('part_id',$id)->orderBy('id','desc')->first();
      $status = false;
      $machine = false;
      $active = false;
      $brand = false;
      $new = false;

      if($history){
        //checamos si ya tiene un status asignado
        if($history->lkp_status_id == NULL){
          $status = $part->lkp_status_id == NULL ? false : true;
        }
        else {
          if($part->lkp_status_id != NULL)
            $status = $part->lkp_status_id == $history->lkp_status_id ? false : true;
        }
        //checamos si ya tiene una maquina asignada
        if($history->machine_id == NULL){
          $machine = $part->machine_id == NULL ? false : true;
        }else {
          if($part->machine_id != NULL)
            $machine = $part->machine_id == $history->machine_id ? false : true;
        }

        if($history->brand_id == NULL){
          $brand = $part->brand_id == NULL ? false : true;
        }
        else {
          if($part->brand_id != NULL)
            $brand = $part->brand_id == $history->brand_id ? false : true;
        }
        //checamo si activo o desactivo la pieza
        $active = $part->active != $history->active ? true : false;

        if($machine || $status || $active || $brand){
          $new = true;
        }
      }
      else {
        $new = true;
      }
      if($new){
        $history = new PartHistory;
        $history->part_id = $part->id;
        $history->brand_id = $part->brand_id;
        $history->lkp_status_id = $part->lkp_status_id;
        $history->machine_id = $part->machine_id;
        $history->active = $part->active;
        $history->created_at = date('Y-m-d H:i:s');
        $history->user_id = Auth::id();
        $history->save();
      }
    }

    public function getIDExcel($values, $option){
        $id =  null;
        switch ($option) {
            case 0://Part Type
                $qry = "select * from lookups where type='part_type' and value = '".$values[0]."';";
            break;
            case 2://Status
                $qry = "select * from lookups where type='status_parts' and value='".$values[0]."'";
            break;
            case 3://Machine Brand
                $qry = "select * from machine_brands where lkp_type_id=54 and brand='".$values[0]."' and model='".$values[1]."';";
            break;
            case 4://Machine
                $qry = "select * from machines where id=".$values[0];
            break;
        }

        $row = DB::select($qry);
        if($row != null)
            $id = $row[0]->id;
        else{
            if($option != 4)
                $id = $this->insertIDExcel($values,$option);
        }
        return $id;
    }

    public function insertIDExcel($values, $option){
        $id =  null;
        switch ($option) {
            case 0:
                $arr = ['type'=>'part_type', 'value' => $values[0], 'band_add'=>0,
                        'active'=>1,'key_value'=> preg_replace('/[0-9\@\.\;\" "]+/', '', strtolower($values[0]))];
                $res = Lookup::create($arr);
            break;
            case 2:
                $arr = ['type'=>'status_parts', 'value' => $values[0], 'band_add'=>0,
                        'active'=>1,'key_value'=> preg_replace('/[0-9\@\.\;\" "]+/', '', strtolower($values[0]))];
                $res = Lookup::create($arr);
            break;
            case 3:                
                $arr = ['brand' => $values[0], 'model'=>$values[1],'active'=>1,'lkp_type_id'=>54];
                $res = MachineBrand::create($arr);
            break;
        }
        if($res != null)
            $id = $res->id;
        return $id;
    }

    public function readExcel(){
        $dir = storage_path('app');
        $file = $dir."/Libro.xlsx";
        $data = Excel::toArray([], $file);
        /*
            0 => Part Type
            1 => Serial
            2 => Price
            3 => Description
            4 => Status
            5 => Brand
            6 => Machine
            7 => Details
        */
        $config = [
            ['pos'=>0,'msg'=>",PART NO ENCONTRADO,",'option'=>0, 'band'=>1],
            ['pos'=>4,'msg'=>",STATUS NO ENCONTRADO,",'option'=>2, 'band'=>1],
            ['pos'=>5,'msg'=>"|BRAND & MODEL NO ENCONTRADO|",'option'=>3, 'band'=>1],
            ['pos'=>6,'msg'=>",MACHINE NO ENCONTRADO,",'option'=>4, 'band'=>1]
        ];
        $i=0;
        foreach ($data[0] as $dt) {
            if($i != 0 && $i != 2846){
                $part_id = null; $status_id = null; $brand_id=null; $machine_id = null;
                foreach ($config as $cf) {
                    $value = $dt[$cf['pos']];
                    if($cf['band'] && $value != "" && $value != null){                        
                        switch ($cf['option']) {
                            case 0://part
                                $part_id = $this->getIDExcel([$value],$cf['option']); 
                                if($part_id == null)
                                    echo "ROW = ".($i+1).$cf['msg'].$value."\n";
                            break;
                            case 2: //status
                                $status_id = $this->getIDExcel([$value],$cf['option']); 
                                if($status_id == null)
                                    echo "ROW = ".($i+1).$cf['msg'].$value."\n";
                            break;
                            case 3://brand
                                $value = str_replace(" - ","-",$value);
                                $aux = explode('-', $value);   
                                $brand = $aux[0];                             
                                if (str_contains($aux[0], 'B:'))                       
                                    $brand = substr($aux[0], 2);
                                if(substr($brand, -1) == " ")
                                    $brand = substr($brand,0,-1);
                                $model = "";
                                for($j=1; $j <count($aux); $j++){                                  
                                    if(substr($aux[$j], 0,1) == " ")
                                        $aux[$j] = substr($aux[$j], 1);
                                    if($model != "")
                                        $model .= " ";
                                    $model .= $aux[$j];
                                }                                    
                                $brand_id = $this->getIDExcel([$brand,$model],$cf['option']);
                                if($brand_id == null)
                                    echo "ROW = ".($i+1)."|BRAND = ".$brand." MODEL = ".$model." NO ENCONTRADO|".$value."\n";
                                
                            break;
                            case 4://machine
                                if($value != ""){
                                    $machine_id = $this->getIDExcel([$value],$cf['option']);            
                                    if($machine_id == null)
                                        echo "ROW = ".($i+1).$cf['msg'].$value."\n"; 
                                }else
                                    echo "ROW = ".($i+1).$cf['msg'].$value."\n";
                            break;
                        }
                        
                    }/*else//if band
                        echo "ROW = ".($i+1)." VACIO ".$cf['msg'].$value."\n"; */
                }//for cf
                if($part_id != null){
                    $serial = null; $description = null; $price = null;
                    /*if($dt[1] != null && $dt[1] != "")
                        $serial = $dt[1];*/
                    if($dt[2] != null && $dt[2] != "")
                        $price = $dt[2];
                    if($dt[3] != null && $dt[3] != "")
                        $description = $dt[3];
            
                    $arr =['lkp_type_id'=>$part_id,'serial'=>$serial,'description' =>$description,
                    'lkp_status_id' =>$status_id,'active'=>1,'machine_id'=>$machine_id,
                    'brand_id' => $brand_id,'price'=>$price];
                    $part = Part::create($arr);
                    echo "ROW = ".($i+1)." ID = ".$part->id."\n";
                    if($part != null)
                        $this->insertPartHistory($part->id);
                }else
                    echo "PART VACIO ROW = ".($i+1)."\n";
            }/*else
                print_r($dt);*/
            $i++;
        }
        return "Termine";
    }
}
