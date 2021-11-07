<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $res = [];
        switch ($request->option) {
            default:
                $res = $this->searchWithFilters($request->all());
            break;
        }
        $types =   DB::table('lookups')->where('type','type_permit')->where('active',1)->get();
        return view('permissions.index',compact('res','types'));    
    }

    public function searchWithFilters($params){
        $qry = "select * from (select p.*, concat(m.id,' - ',l.value, ' - ', ifnull(m.serial,'')) as game,m.active,(select value from lookups where id=p.lkp_type_permit_id) as type
            from permissions p, machines m, lookups l where p.machine_id = m.id and l.id = m.lkp_game_id) as tab1 where tab1.active=1";
        if(count($params) > 0){
            if($params['type'] != "")
                $qry .= " and tab1.lkp_type_permit_id = ".$params['type'];
            if($params['machine'] != "")
                $qry .= " and tab1.game like '%".$params['machine']."%'";
            if($params['number'] != "")
                $qry .= " and tab1.permit_number like '%".$params['number']."%'";
        }            
        $qry .= " order by tab1.id desc;";
        return DB::select($qry);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types =  DB::table('lookups')->where('type','type_permit')->get();
        $qry = "select m.*,l.value from machines m, lookups l where m.lkp_game_id=l.id and m.active = 1 and m.id not in (select machine_id from permissions) and m.lkp_owner_id = 38;";
        $machines = DB::select($qry);
        return view('permissions.create',compact('types','machines'));
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
            'machine_id' => 'required',
            'lkp_type_permit_id' => 'required',
            'permit_number' => 'required|unique:permissions,permit_number|nullable',
            'validate_permit_number' => 'required'
        ]);   
        if($request->validate_permit_number != $request->permit_number){
            $transaction = array(
              'message' => 'The permission numbers do not match.',
              'alert-type' => 'error'
            );
            return back()->with($transaction)->withInput($request->all());
        }
        try{
            $transaction = DB::transaction(function() use($request){                   
                $arr = $request->except('_token','validate_permit_number');  
                $arr['year_permit'] = date('Y');       
                $permission = Permission::create($arr);
                if ($permission) {
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

            return redirect()->action('PermissionController@index')->with($transaction);
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';
            $message = $e->getMessage();
            $pos = strpos($message, 'permissions.permit_number');            
            if ($pos != false) 
                $cad = "The Permit Number must be unique.";           
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
        $permission = Permission::findOrFail($id);
        $types =  DB::table('lookups')->where('type','type_permit')->get();
        $qry = "select m.*,l.value from machines m, lookups l where m.lkp_game_id=l.id and m.active = 1 and m.id not in (select machine_id from permissions where id != ".$id.") and m.lkp_owner_id = 38;";
        $machines = DB::select($qry);
        return view('permissions.edit',compact('types','machines','permission'));
    }

    public function getUpdateYearPermit($year){
        $res = $year;
        $period = date('m');
        if($period >= 10)
            $res = date('Y') + 1;
        return $res;
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
            'lkp_type_permit_id' => 'required',
            'permit_number' => 'required|nullable|unique:permissions,permit_number,'.$id,
            'validate_permit_number' => 'required'
        ]); 
        if($request->validate_permit_number != $request->permit_number){
            $transaction = array(
              'message' => 'The permission numbers do not match.',
              'alert-type' => 'error'
            );
            return back()->with($transaction)->withInput($request->all());
        } 
        try{
            $transaction = DB::transaction(function() use($request, $id){                  
                $arr = $request->except('_token','_method','validate_permit_number');                
                $permission = Permission::findOrFail($id);
                $arr['year_permit'] = $this->getUpdateYearPermit($permission->year_permit);
                $permission->update($arr);
                $permission->save();
                if ($permission) {
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

            return redirect()->action('PermissionController@index')->with($transaction);
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';
            $message = $e->getMessage();
            $pos = strpos($message, 'permission.permit_number');            
            if ($pos != false) 
                $cad = "The Permit Number must be unique.";          
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
         $transaction = DB::transaction(function() use($id){
            Permission::destroy($id);
            return response()->json(200);
         });
         return $transaction;
    }
}
