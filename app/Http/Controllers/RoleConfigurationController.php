<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;
use App\User;
use DB;
use App\Models\Lookup;
use Illuminate\Support\Facades\Mail;

class RoleConfigurationController extends Controller
{
  public function index($rol = null){
    $role = null;
    if($rol)
      $role = Lookup::where('key_value',$rol)->first();
    $roles = Lookup::where('type','roles')->get();
    $menus = Lookup::where('type','menus')->get();
    //dd($roles, $menus, $rol, $role);
    return view('main.rolesConfiguration',compact('roles','menus','rol','role'));
  }

  public function rolesConfigurationSave(Request $request){
    $x = count($request->menu);
    for ($i=0; $i < $x; $i++) {
      $str = '';

      if($request->read[$i] == 1){
        $str = $str.'R,';
      }

      if($request->create[$i] == 1){
        $str = $str.'C,';
      }

      if($request->update[$i] == 1){
        $str = $str.'U,';
      }

      if($request->delete[$i] == 1){
        $str = $str.'D,';
      }



      $mr = DB::table('menu_roles')->where('lkp_menu_id',$request->menu[$i])->where('lkp_role_id',$request->role[$i])->first();
      if($mr){
        DB::table('menu_roles')->where('lkp_menu_id',$request->menu[$i])->where('lkp_role_id',$request->role[$i])->update(['actions' => $str]);
      }
      else {
        DB::insert('insert into menu_roles (lkp_menu_id, actions, lkp_role_id) values (?, ?, ?)', [$request->menu[$i],$str,$request->role[$i]]);
      }
      DB::table('menu_roles')->where('actions','')->delete();
      $notification = array(
        'message' => 'Successful!!',
        'alert-type' => 'success'
      );

    }

    return back()->with($notification);

  }
}
