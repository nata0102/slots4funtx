<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;
use App\User;
use DB;
use App\Models\Lookup;
use Illuminate\Support\Facades\Mail;

use App\Mail\Prueba;

class MainController extends Controller
{

  public function index(){


    if(Auth::guest()){
      return view('main.login');
    }
    else {



      return view('main.index');


    }
  }

  public function logout(Request $request) {
    Auth::logout();
    return redirect('/');
  }

  public function login(Request $request){

    $password = $request->password;
    if(str_contains($request->email, '@')){
      $email = $request->email;
      $user = User::where('email', $request->email)->first();
      if($user){
 
          //$mailable = new Prueba($user);
          //Mail::to("rodrigo.pescina.91@gmail.com")->send($mailable);

        if ($user->active == 0) {
          return back() -> withErrors(['email'=>'No se ha encontrado un usuario con esa dirección de correo o número teléfonico.']);
        }
      }
    }
    else{
      $user = User::where('phone', $request->email)->first();
      if($user){
        if ($user->active == 0) {
          return back() -> withErrors(['email'=>'No user found with that email address or phone number.']);
        }
        $email = $user->email;
      }
      else
        return back() -> withErrors(['email'=>'No user found with that email address or phone number.']);
    }

    $credentials = [
      'email' => $email,
      'password' => $password,
    ];

    if (Auth::attempt($credentials)) {
      return redirect()->action('MainController@index');
    }
    else {
      return back() -> withErrors(['email'=>'No user found with that email address or phone number.']);
    }
  }

  public function rolesConfiguration($rol = null){
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

    }

    return back();

  }
}
