<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Support\Facades\Mail;

class MainController extends Controller
{
  public function index(){
    if(Auth::guest()){
      return view('main.login');
    }
    else {
      dd("logeado");
    }
  }

  public function logout(Request $request) {
    Auth::logout();
    return redirect('/');
  }

  public function login(Request $request){
    $credentials = [
      'email' => $request->email,
      'password' => $request->password,
    ];

    if (Auth::attempt($credentials)) {
      dd("logeado");
    }
    else {
      return back() -> withErrors(['email'=>'No se ha encontrado un usuario con esa dirección de correo.']);
    }
  }
}
