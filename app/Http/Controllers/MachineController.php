<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\MachineHistory;
use App\Models\Part;
use App\Models\PercentagePriceMachine;
use Illuminate\Support\Facades\DB;


class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $res = [];

        switch($request->option){
            case 'all':
               $res = $this->searchWithFilters($request->all());
            break;
            default:
               $res = Machine::with('status','address.client','brand','owner')->where('active',1)->orderBy('id','desc')->take(20)->get();
            break;
        }
        return view('machines.index',compact('res'));
    }

    public function searchWithFilters($params){
        $res = [];
        $aux = Machine::with([
            'status' => function ($query) use($params){
                if($params['status'])
                    $query->where('value', 'LIKE', "%{$params['status']}%");
            },
            'address.client',
            'brand' => function($query) use ($params){
                if($params['brand'])
                    $query->where('brand', 'LIKE', "%{$params['brand']}%")
                    ->orWhere('model', 'LIKE', "%{$params['brand']}%")
                    ->orWhere('weight', 'LIKE', "%{$params['brand']}%");
            },
            'owner' => function($query) use($params){
                if($params['owner'])
                    $query->where('value', 'LIKE', "%{$params['owner']}%");
            }])->game($params['game'])->where('active',$params['active'])->get();
        foreach ($aux as $a) {
            $b_owner = true;
            if($params['owner']){
                if($a->owner == null)
                    $b_owner = false;
            }
            $b_status = true;
            if($params['status']){
                if($a->status == null)
                    $b_status = false;
            }
            $b_brand = true;
            if($params['brand']){
                if($a->brand == null)
                    $b_brand = false;
            }
            if($b_owner == true && $b_status == true && $b_brand == true)
                array_push($res,$a);
        }
        return $res;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $owners =  DB::table('lookups')->where('type','owner_type')->get();
        $status =  DB::table('lookups')->where('type','status_machines')->get();
        $addresses = DB::table('addresses')->join('clients', 'addresses.client_id', '=', 'clients.id')
                    ->select('addresses.*','clients.name')->get();
        $brands = DB::table('machine_brands')->get();
        $parts = DB::table('parts')->whereNull('machine_id')->where('active',1)->join('lookups', 'parts.lkp_type_id', '=', 'lookups.id')->select('parts.*','lookups.value')->orderBy('serial')->get();
        return view('machines.create',compact('owners','addresses','status','brands','parts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){  
        try{
            $transaction = DB::transaction(function() use($request){  
                $this->validate($request, [
                    'game_title' => 'required',
                    'lkp_owner_id' => 'required',
                    'serial' => 'unique:machines,serial|nullable',
                    'inventory' => 'unique:machines,inventory|nullable',

                ]);               
                $arr = $request->except('parts_ids','_token','image');            
                $parts = $request->parts_ids;
                if($request->image)
                    $arr['image'] = $this->saveGetNameImage($request->image,'/images/machines/');
                $machine = Machine::create($arr);
                if($parts != null){
                    foreach ($parts as $part) {
                        $part = Part::findOrFail(intval($part));
                        $part->machine_id = $machine->id;
                        $part->save();
                        $this->insertPartHistory(intval($part));
                    }
                }
                if ($machine) {
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
                $this->insertMachineHistory($machine);
                return $notification;
            });

            return redirect()->action('MachineController@index')->with($transaction);
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';
            $message = $e->getMessage();
            $pos = strpos($message, 'machines.serial');            
            if ($pos != false) 
                $cad = "The Serial must be unique.";
            $pos = strpos($message, 'machines.inventory');            
            if ($pos != false) 
                $cad = "The Inventory must be unique.";            
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
        $machine = Machine::with('status','address.client','brand','owner','parts.type')->findOrFail($id);
        return view('machines.show',compact('machine'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $machine = Machine::findOrFail($id);
        $owners =  DB::table('lookups')->where('type','owner_type')->get();
        $status =  DB::table('lookups')->where('type','status_machines')->get();
        $addresses = DB::table('addresses')->join('clients', 'addresses.client_id', '=', 'clients.id')
                    ->select('addresses.*','clients.name')->get();
        $brands = DB::table('machine_brands')->get();
        $parts = DB::table('parts')->whereNull('machine_id')->orWhere('machine_id',$id)->where('active',1)->join('lookups', 'parts.lkp_type_id', '=', 'lookups.id')->select('parts.*','lookups.value')->orderBy('serial')->get();
        $parts_on_machine = DB::table('parts')->where('machine_id',$id)->where('active',1)->get();
        $parts_ids = [];
        foreach ($parts_on_machine as $p_machine )
            array_push($parts_ids,(string) $p_machine->id);
        
        return view('machines.edit',compact('owners','addresses','status','brands','parts','machine','parts_ids'));   
    }

    public function updateMachineParts($parts, $machine_id){
        $parts_machine = Part::where('machine_id',$machine_id)->get(); 
        foreach ($parts_machine as $p_machine) {
            if ($parts == null || !in_array((string)$p_machine->id, $parts)){
                $p_machine->machine_id = null;
                $p_machine->save();  
                $this->insertPartHistory($p_machine->id);                   
            }                
        }
        if($parts != null){
            foreach ($parts as $part){
                $part = Part::findOrFail(intval($part));
                $part->machine_id = $machine_id;
                $part->save();
                $this->insertPartHistory(intval($part));
            }
        }
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
            $transaction = DB::transaction(function() use($request, $id){  
                $this->validate($request, [
                    'game_title' => 'required',
                    'lkp_owner_id' => 'required',
                    'serial' => 'nullable|unique:machines,serial,'.$id,
                    'inventory' => 'nullable|unique:machines,inventory,'.$id,
                ]); 
                $arr = $request->except('parts_ids','_token','image','_method');            
                $parts = $request->parts_ids;
                $this->updateMachineParts($parts, $id);
                $machine = Machine::findOrFail($id);
                if($request->image){
                    $arr['image'] = $this->saveGetNameImage($request->image,'/images/machines/');
                    if($machine->image != null)
                        unlink(public_path().'/images/machines/'.$machine->image);
                }
                $machine->update($arr);
                $machine->save();
                $this->insertMachineHistory($machine);
                if ($machine) {
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

            return redirect()->action('MachineController@index')->with($transaction);
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';
            $message = $e->getMessage();
            $pos = strpos($message, 'machines.serial');            
            if ($pos != false) 
                $cad = "The Serial must be unique.";
            $pos = strpos($message, 'machines.inventory');            
            if ($pos != false) 
                $cad = "The Inventory must be unique.";            
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
        try{
            $transaction = DB::transaction(function() use ($id){
                Part::where('machine_id',$id)->update(['active'=>0]);
                $parts = Part::where('machine_id',$id)->get();
                foreach ($parts as $part) 
                    $this->insertPartHistory($part->id);

                $machine = Machine::findOrFail($id);
                $machine->active = 0;
                if($machine->save()){
                   $this->insertMachineHistory($machine);
                   return response()->json(200);
                }else
                   return response()->json(422);
            });
            return $transaction;
        }catch(\Exception $e){
            return response()->json(422);
        }
    }

    public function active($id)
    {
        try{
            return DB::transaction(function() use ($id){
                $res = Machine::findOrFail($id);
                $res->active = 1;
                $update = $res->save();
                Part::where('machine_id',$id)->update(['active'=>1]);
                $parts = Part::where('machine_id',$id)->get();
                foreach ($parts as $part) 
                    $this->insertPartHistory($part->id);
                
                if ($update) 
                    return response()->json(array('success' => true), 200);
                else 
                    return response()->json(array('success' => false,'errors' => 'Oops! there was an error, please try again later.'), 422);                
            });
        }catch(\Exception $e){
            return response()->json(array('success' => false,'errors' => 'Oops! there was an error, please try again later.'), 422);
        }
    }
}
