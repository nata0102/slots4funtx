<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Machine;


class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $machines = Machine::select('machines.*','permissions.*')->join('permissions','machines.id','permissions.machine_id')->get();

      $res = [];
      switch ($request->option) {
          default:
              $res = $this->searchWithFilters($request->all());
          break;
      }
      $types =   DB::table('lookups')->where('type','type_permit')->where('active',1)->get();
      return view('permissions.index',compact('res','types','machines'));
    }

    public function searchWithFilters($params){
        $qry = "select * from(
                select *,
                (select value from lookups where id = p.lkp_type_permit_id) as type,
                (select concat(id,' - ', ifnull(serial,'')) from machines where
                id = p.machine_id and active=1) as game,
                (select active from machines where id = p.machine_id) as active
                from permissions p) as tab1 where (tab1.active = 1 or tab1.active is null)";
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
        /*$qry = "select m.*,l.value from machines m, lookups l where m.lkp_game_id=l.id and m.active = 1 and m.id not in (select machine_id from permissions) and m.lkp_owner_id = 38;";
        $machines = DB::select($qry);*/
        $qry = "select m.*,l.value from machines m, lookups l where m.active = 1 and m.lkp_owner_id = 38 and m.lkp_owner_id = l.id and m.id not in (select machine_id from permissions);";
        $machines = DB::select($qry);

        $permissions = Permission::all();


        return view('permissions.create',compact('types','machines'));
    }

    public function createByRank(){
        $types =  DB::table('lookups')->where('type','type_permit')->get();
        return view('permissions.createByRank',compact('types'));
    }

    public function storeByRank(Request $request){
        $this->validate($request, [
            'lkp_type_permit_id' => 'required',
            'start_range' => 'required',
            'final_range' => 'required',
            'year_permit' => 'required'
        ]);
        if($request->final_range < $request->start_range){
            $transaction = array(
                'message' => 'The final range must be greater than the initial range.',
                'alert-type' => 'error'
            );
            return back()->with($transaction)->withInput($request->all());
        }
        try{
            $transaction = DB::transaction(function() use($request){
                $arr = $request->except('_token');
                for($i = $arr['start_range']; $i<= $arr['final_range']; $i++) {
                    Permission::create(['permit_number'=>$i,'lkp_type_permit_id'=>$arr['lkp_type_permit_id'],'year_permit'=>$arr['year_permit']]);
                }
                $notification = array(
                  'message' => 'Successful!!',
                  'alert-type' => 'success'
                );
                return $notification;
            });

            return redirect()->action('PermissionController@index')->with($transaction);
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
                $this->insertMachineHistory($permission->machine_id);
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
        $machines = [];
        $machine = null;
        if($permission->machine_id != null )
            $machine = Machine::findOrFail($permission->machine_id);
        else{
          $qry = "select m.*,l.value from machines m, lookups l where m.active = 1 and m.lkp_owner_id = 38 and m.lkp_owner_id=l.id and m.id not in (select machine_id from permissions);";
          $machines = DB::select($qry);
        }
        return view('permissions.edit',compact('types','machines','permission','machine'));
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
                $permission->update($arr);
                $permission->save();
                if($permission->machine_id != null)
                    $this->insertMachineHistory($permission->machine_id);
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
            $permission = Permission::findOrFail($id);
            Permission::destroy($id);
            if($permission->machine_id != null)
                $this->insertMachineHistory($permission->machine_id);
            return response()->json(200);
         });
         return $transaction;
    }
}
