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
        return view('permissions.index',compact('res'));    
    }

    public function searchWithFilters($params){
        $qry = "select * from (select p.*,l.value as type_value,(select game_title from machines where id=p.machine_id) as machine_name from permissions p, lookups l where p.lkp_type_permit_id = l.id)  as tab1";
        if(count($params) > 0)
            $qry .= " where ";
        if (array_key_exists('type', $params))
            $qry .= " tab1.type_value like '%".$params['type']."%'";
        if (array_key_exists('machine', $params))
            $qry .= " and tab1.machine_name like '%".$params['machine']."%'";
        if (array_key_exists('number', $params))
            $qry .= " and tab1.permit_number like '%".$params['number']."%'";
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
        $machines =  DB::table('machines')->where('active',1)->orderBy('game_title')->get();
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
            'date_permit' => 'required',
            'permit_number' => 'unique:permissions,permit_number|nullable',
        ]);    
        try{
            $transaction = DB::transaction(function() use($request){                             
                $arr = $request->except('_token');         
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
        $machines =  DB::table('machines')->where('active',1)->orderBy('game_title')->get();
        return view('permissions.edit',compact('types','machines','permission'));
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
            'machine_id' => 'required',
            'lkp_type_permit_id' => 'required',
            'date_permit' => 'required',
            'permit_number' => 'nullable|unique:permissions,permit_number,'.$id,
        ]);  
        try{
            $transaction = DB::transaction(function() use($request, $id){                  
                $arr = $request->except('_token','_method');            
                $permission = Permission::findOrFail($id);
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
