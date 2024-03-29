<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\MachineHistory;
use App\Models\GameCatalog;
use App\Models\Part;
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
            case 'flat_percentage_last_numbers':
                $res = $this->getMachinesFlatPercentage();
            break;
            default:
               $res = Machine::with('status','address.client','brand','owner','game','permissionActual')->where('active',1)->orderBy('id')->get();
            break;
        }

        $games = DB::table('game_catalog')->where('active',1)->orderBy('name')->get();
        $owners =  DB::table('lookups')->where('type','owner_type')->orderBy('value')->get();
        //$status =  DB::table('lookups')->where('type','status_machines')->where('active',1)->orderBy('value')->get();
        $qry = "select *, ifnull((select value from lookups where id = t1.id),'NO STATUS') as value
                from (select lkp_status_id as id,count(*) as total
                from machines group by lkp_status_id) as t1;";
        $status = DB::select($qry);
        $brands =  DB::table('machine_brands')->where('lkp_type_id',53)->where('active',1)->orderBy('brand')->orderBy('model')->get();
        $qry = "select count(*) as total, (select id from machines order by id desc limit 1) as id
                from machines;";
        $totales =  DB::select($qry)[0];
        $qry = "select * from (select *,(select name from clients where id=a.client_id) as name_client from addresses a where id in (select address_id from machines) order by name_address) as t order by t.name_client, t.name_address;";
        $business = DB::select($qry);
        return view('machines.index',compact('res','owners','status','brands','games','totales','business'));
    }

    public function getMachinesFlatPercentage($address_id){
        $qry = "select m.*,(select value from lookups where id=m.lkp_owner_id) as owner,
        (select name from game_catalog where id=m.game_catalog_id) as game, m.band_jackpot,
        (select master_in from charges where machine_id = m.id order by id desc limit 1) as master_in,
        (select master_out from charges where machine_id = m.id order by id desc limit 1) as master_out, 
        (select jackpot_out from charges where machine_id = m.id order by id desc limit 1) as jackpot_out,
        (select avg(utility_s4f) from charges where machine_id =m.id and utility_s4f is not null order by id desc limit 5) as average,
        (select amount from percentage_price_machine where machine_id=m.id) as percentage 
        from machines m where m.active = 1 and m.id in (select machine_id from percentage_price_machine where lkp_type_id = 45) and address_id = ".$address_id.";";
        return DB::select($qry);
    }

    public function getPartsToAssign($id = null){
        $qry = "select t1.* from (";
        /*parts de una maquina*/
        if($id != null){
            $qry .= "select p.id,p.serial,(select value from lookups where id=p.lkp_type_id) as value,
            (select concat(brand,' ',model) from machine_brands where id=p.brand_id) as brand,
            (select name_image from images_brands where part_id=p.lkp_type_id and brand_id = p.brand_id 
            limit 1) as image from parts p where machine_id=".$id." and active=1 union ";
        }

        /*Parts con serial y sin maquina asignada*/
        $qry .= "select p.id,p.serial,(select value from lookups where id=p.lkp_type_id) as value,
        (select concat(brand,' ',model) from machine_brands where id=p.brand_id) as brand,
        (select name_image from images_brands where part_id=p.lkp_type_id and brand_id = p.brand_id 
        limit 1) as image from parts p where serial is not null and machine_id is null and active=1 ";

        /*Parts sin serial, sin maquina y agrupadas por lkp_type_id y brand_id*/
        $qry2 = "select lkp_type_id, brand_id,
        (select id from parts where lkp_type_id = p.lkp_type_id and brand_id=p.brand_id
        and serial is null and machine_id is null and active=1 limit 1) as id
        from parts p where serial is null and machine_id is null and active=1 and brand_id is not null
        group by lkp_type_id, brand_id
        union
        select lkp_type_id, brand_id,
        (select id from parts where lkp_type_id = p.lkp_type_id and brand_id is null 
        and serial is null and machine_id is null and active=1  limit 1) as id
        from parts p where serial is null and machine_id is null and active=1 and brand_id is null
        group by lkp_type_id, brand_id";
        $rows = DB::select($qry2);
        $ids = [];
        foreach ($rows as $r) 
            array_push($ids,$r->id);
        if(count($ids) > 0)
            $qry .= " union select p.id,p.serial,(select value from lookups where id=p.lkp_type_id) as value,(select concat(brand,' ',model) from machine_brands where id=p.brand_id) as brand,
            (select name_image from images_brands where part_id=p.lkp_type_id and brand_id = p.brand_id 
            limit 1) as image from parts p where id in (".implode(',', $ids).")";
        $qry .= ") as t1 order  by t1.value, t1.brand;";
        return DB::select($qry);        
    }

    public function searchWithFilters($params){
        return Machine::with(['status','game','address.client','brand','owner','permissionActual'])
               ->statussearch($params['status'])->machine($params['game'])->brand($params['brand'])
               ->owner($params['owner'])->where('active',$params['active'])->serial($params['serial'])->address($params['business'])->id($params['id'])->inventory($params['inventory'])
               ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( url()->previous() != url()->current() ){
            session()->forget('urlBack');
            session(['urlBack' => url()->previous()]);
        }
        $games = GameCatalog::with('brands.brand','type')->where('active',1)->orderBy('name')->get();
        $owners =  DB::table('lookups')->where('type','owner_type')->orderBy('value')->get();
        $status =  DB::table('lookups')->where('type','status_machines')->where('active',1)->orderBy('value')->get();
        $addresses = DB::table('addresses')->join('clients', 'addresses.client_id', '=', 'clients.id')->where('addresses.active',1)->where('clients.active',1)
                    ->select('addresses.*','clients.name')->orderBy('clients.name')->get();
        $brands =  DB::table('machine_brands')->where('lkp_type_id',53)->where('active',1)->orderBy('brand')->orderBy('model')->get();
        /*$qry = "select p.id,p.serial,(select value from lookups where id=p.lkp_type_id) as value,
                (select concat(brand,' ',model) from machine_brands where id=p.brand_id) as brand,
                (select name_image from images_brands where part_id=p.lkp_type_id and brand_id = p.brand_id limit 1) as image
                from parts p where machine_id is null and active=1 order by serial;";
        $parts = DB::select($qry);*/
        $parts = $this->getPartsToAssign();
        $qry = "select (id+1) as id from machines order by id desc limit 1;";
        $row = DB::select($qry);
        $id = 1;
        if(count($row) > 0)
            $id =  $row[0]->id;
        return view('machines.create',compact('owners','addresses','status','brands','parts','games','id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'lkp_owner_id' => 'required',
            'serial' => 'unique:machines,serial|nullable',
            'inventory' => 'unique:machines,inventory|nullable',
        ]);   
        try{
            $transaction = DB::transaction(function() use($request){                             
                $arr = $request->except('parts_ids','_token','image','games_select',
                    'old_machine_brand_id');
                if($arr['serial']!=null && $arr['serial']!="")
                    $arr['serial'] = strtoupper($arr['serial']);
                if($arr['inventory']!=null && $arr['inventory']!="")
                    $arr['inventory'] = strtoupper($arr['inventory']);
                
                $arr['games'] = strtoupper($arr['games']);
                $arr['notes'] = strtoupper($arr['notes']);
                if(array_key_exists('games_select', $request->all())){
                     $arr['games'] = "";
                    foreach ($request->games_select as $g_select) 
                        $arr['games'] .= $g_select."&$";                    
                }            
                $parts = $request->parts_ids;
                if($request->image)
                    $arr['image'] = $this->saveGetNameImage($request->image,'/images/machines/');
                $machine = Machine::create($arr);
                if($parts != null){
                    foreach ($parts as $part_id) {
                        $part = Part::findOrFail(intval($part_id));
                        $part->machine_id = $machine->id;
                        $part->save();
                        $this->insertPartHistory(intval($part_id));
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
                $this->insertMachineHistory($machine->id);
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
        if( url()->previous() != url()->current() ){
            session()->forget('urlBack');
            session(['urlBack' => url()->previous()]);
          }
        $machine = Machine::with('game','status','address.client','brand','owner','parts.type','parts.brand')->findOrFail($id);
        foreach($machine->parts as $part){
            $image = DB::table('images_brands')->where('part_id',$part->lkp_type_id)->where('brand_id',$part->brand_id)->first();
            if($image != null)
                $part->image = $image->name_image;
        }
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
        if( url()->previous() != url()->current() ){
            session()->forget('urlBack');
            session(['urlBack' => url()->previous()]);
          }
        $games = GameCatalog::with('brands.brand', 'type')->where('active',1)->orderBy('name')->get();
        $machine = Machine::findOrFail($id);
        $owners =  DB::table('lookups')->where('type','owner_type')->orderBy('value')->get();
        $status =  DB::table('lookups')->where('type','status_machines')->where('active',1)->orderBy('value')->get();
        $addresses = DB::table('addresses')->join('clients', 'addresses.client_id', '=', 'clients.id')
                    ->where('addresses.active',1)->where('clients.active',1)
                    ->select('addresses.*','clients.name')->orderBy('clients.name')->get();
        $brands =  DB::table('machine_brands')->where('lkp_type_id',53)->where('active',1)->orderBy('brand')->orderBy('model')->get();
        /*$qry = "select p.id,p.serial,(select value from lookups where id=p.lkp_type_id) as value,
        (select concat(brand,' ',model) from machine_brands where id=p.brand_id) as brand,
        (select name_image from images_brands where part_id=p.lkp_type_id and brand_id=p.brand_id limit 1) as image
        from parts p where (machine_id is null or machine_id=".$id.") and active=1 order by serial;";
        $parts = DB::select($qry);*/
        $parts = $this->getPartsToAssign($id);
        $parts_on_machine = DB::table('parts')->where('machine_id',$id)->where('active',1)->orderBy('serial')->get();
        $parts_ids = [];
        foreach ($parts_on_machine as $p_machine )
            array_push($parts_ids,(string) $p_machine->id);
        
        return view('machines.edit',compact('owners','addresses','status','brands','parts','machine','parts_ids','games'));   
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
            foreach ($parts as $part_id){
                $part = Part::findOrFail($part_id);
                $part->machine_id = $machine_id;
                $part->save();
                $this->insertPartHistory($part->id);
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
        $this->validate($request, [
            'lkp_owner_id' => 'required',
            'serial' => 'nullable|unique:machines,serial,'.$id,
        ]); 
        try{
            $transaction = DB::transaction(function() use($request, $id){                  
                $arr = $request->except('parts_ids','_token','image','_method','games_select',
                    'old_machine_brand_id');
                if($arr['serial']!=null && $arr['serial']!="")
                    $arr['serial'] = strtoupper($arr['serial']);
                $arr['games'] = strtoupper($arr['games']);
                $arr['notes'] = strtoupper($arr['notes']);
                if(array_key_exists('games_select', $request->all())){
                     $arr['games'] = "";
                    foreach ($request->games_select as $g_select) 
                        $arr['games'] .= $g_select."&$";                    
                }                        
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
                $this->insertMachineHistory($id);
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
                $machine = Machine::findOrFail($id);
                $machine->active = $machine->active == 0 ? 1 : 0;
                if($machine->save()){
                    $this->insertMachineHistory($id);
                    Part::where('machine_id',$id)->update(['active' => $machine->active]);
                    $parts = Part::where('machine_id',$id)->get();
                    foreach ($parts as $part) 
                        $this->insertPartHistory($part->id);
                    return response()->json(200);
                }else
                    return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
            });
            return $transaction;
        }catch(\Exception $e){
            return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
        }
    }
}
