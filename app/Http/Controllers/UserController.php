<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Models\Lookup;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

  public function index(Request $request){
      $res = [];

      switch($request->option){
          case 'all':
            $res = $this->searchWithFilters($request->all());
          break;
          default:
            $res = User::with('role','client')->where('active',1)->orderBy('id','desc')->get();
          break;
      }

      $roles =  DB::table('lookups')->where('type','roles')->orderBy('value')->get();

      return view('users.index',compact('res','roles'));
  }

  public function create()
  {
    if( url()->previous() != url()->current() ){
        session()->forget('urlBack');
        session(['urlBack' => url()->previous()]);
    }
    $roles =  DB::table('lookups')->where('type','roles')->orderBy('value')->get();
    $clients = DB::select("select * from clients where id not in (select client_id from users where client_id is not null ) and active=1 order by name;");
    return view('users.create',compact('roles','clients'));
  }

  public function store(Request $request)
    {
        $this->validate($request, [
            'lkp_rol_id' => 'required',
            'email' => 'unique:users|required',
            'phone' => 'unique:users|required',
            'name' => 'required'
        ]);   
        try{
            $transaction = DB::transaction(function() use($request){                             
                $arr = $request->except('_token','image');  
                $role = Lookup::findOrFail($arr['lkp_rol_id']);
                if($role->key_value=='client') {
                    if($arr['client_id'] == null){
                        $notification =   array(
                          'message' => 'Oops! there was an error, Client cannot be null.',
                          'alert-type' => 'error'
                        );
                        return back()->with($notification)->withInput($request->all());
                    }
                }else
                  $arr['client_id'] = null;
                $arr['name'] = strtoupper($arr['name']);   
                $arr['password'] = Hash::make($arr['password']);                     
                if($request->image)
                    $arr['name_image'] = $this->saveGetNameImage($request->image,'/images/users/');
                $res = User::create($arr);
               
                if ($res) {
                    $notification = array(
                      'message' => 'Successful!!',
                      'alert-type' => 'success'
                    );
                    return redirect()->action('UserController@index')->with($notification);
                }else {
                    $notification = array(
                      'message' => 'Oops! there was an error, please try again later.',
                      'alert-type' => 'error'
                    );
                }
                return back()->with($notification)->withInput($request->all());
            });

            return $transaction;

            
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';
            $message = $e->getMessage();
            $pos = strpos($message, 'users.email');            
            if ($pos != false) 
                $cad = "The Email must be unique.";
            $pos = strpos($message, 'users.phone');            
            if ($pos != false) 
                $cad = "The Phone must be unique.";            
            $transaction = array(
                'message' => $cad.$message,
                'alert-type' => 'error' 
            );
        }

        return back()->with($transaction)->withInput($request->all());
    }

  public function show($id)
    {
        if( url()->previous() != url()->current() ){
            session()->forget('urlBack');
            session(['urlBack' => url()->previous()]);
          }
        $res = User::with('role','client')->findOrFail($id);
        return view('users.show',compact('res'));
    }


    public function edit($id) {
        if( url()->previous() != url()->current() ){
            session()->forget('urlBack');
            session(['urlBack' => url()->previous()]);
        }
        $res = User::with('role','client')->findOrFail($id);
        $qry = "select * from (select * from clients where id not in (select client_id from users where client_id is not null ) and active=1";
        if($res->client_id != null)
          $qry .= " union select * from clients where id=".$res->client_id;
        $qry.=") as t1 order by t1.name";
        $clients = DB::select($qry);
        $roles =  DB::table('lookups')->where('type','roles')->orderBy('value')->get();    
        return view('users.edit',compact('res','roles','clients'));   
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'lkp_rol_id' => 'required',
            'email' => 'required|unique:users,id,'.$id,
            'phone' => 'required|unique:users,id,'.$id,
            'name' => 'required'
        ]); 

        try{
            $transaction = DB::transaction(function() use($request, $id){   
                $arr = $request->except('_token','image'); 
                $role = Lookup::findOrFail($arr['lkp_rol_id']);
                if($role->key_value=='client') {
                    if($arr['client_id'] == null){
                        $notification =   array(
                          'message' => 'Oops! there was an error, Client cannot be null.',
                          'alert-type' => 'error'
                        );
                        return back()->with($notification)->withInput($request->all());
                    }
                }else
                  $arr['client_id'] = null;

                $arr['name'] = strtoupper($arr['name']);  
                if($arr['password'] != "" && $arr['password'] != null) 
                  $arr['password'] = Hash::make($arr['password']);
                else
                  unset($arr['password']);

                if(array_key_exists('games_select', $request->all())){
                     $arr['games'] = "";
                    foreach ($request->games_select as $g_select) 
                        $arr['games'] .= $g_select."&$";                    
                } 

                $res = User::findOrFail($id);

                if($request->image){
                    $arr['name_image'] = $this->saveGetNameImage($request->image,'/images/users/');
                    if($res->name_image != null)
                        unlink(public_path().'/images/users/'.$res->name_image);
                }

                $res->update($arr);
                $res->save();
               
                if ($res) {
                    $notification = array(
                      'message' => 'Successful!!',
                      'alert-type' => 'success'
                    );
                    return redirect()->action('UserController@index')->with($notification);
                }else {
                    $notification = array(
                      'message' => 'Oops! there was an error, please try again later.',
                      'alert-type' => 'error'
                    );
                }
                return back()->with($notification)->withInput($request->all());                
            }); 
            return $transaction;
        }catch(\Exception $e){
            $cad = 'Oops! there was an error, please try again later.';
            $message = $e->getMessage();
            $pos = strpos($message, 'users.email');            
            if ($pos != false) 
                $cad = "The Email must be unique.";
            $pos = strpos($message, 'users.phone');            
            if ($pos != false) 
                $cad = "The Phone must be unique.";            
            $transaction = array(
                'message' => $cad.$message,
                'alert-type' => 'error' 
            );
        }

        return back()->with($transaction)->withInput($request->all());       
    }

  public function destroy($id){
      try{
          $transaction = DB::transaction(function() use ($id){                
              $res = User::findOrFail($id);
              $res->active = $res->active == 0 ? 1 : 0;
              if($res->save())
                  return response()->json(200);
              else
                  return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
          });
          return $transaction;
      }catch(\Exception $e){
          return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
      }
  }

  public function searchWithFilters($params){
        return User::with('role','client')->where('active',$params['active'])
        ->email($params['email_number'])->role($params['role'])
               ->get();
  }

  public function authenticate(Request $request)
  {
    $credentials = $request->only('email', 'password');
    try {
      if (! $token = JWTAuth::attempt($credentials)) {
        return response()->json(['error' => 'invalid_credentials'], 400);
      }
    } catch (JWTException $e) {
      return response()->json(['error' => 'could_not_create_token'], 500);
    }
    return response()->json(compact('token'));
  }

  public function getAuthenticatedUser()
  {
    try {
      if (!$user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['user_not_found'], 404);
      }
    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
      return response()->json(['token_expired'], $e->getStatusCode());
    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
      return response()->json(['token_invalid'], $e->getStatusCode());
    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
      return response()->json(['token_absent'], $e->getStatusCode());
    }
    return response()->json(compact('user'));
  }

  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');

    $validator = Validator::make($credentials, [
      'email' => 'required|email',
      'password' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'code' => 1,
        'message' => 'Wrong validation',
        'errors' => $validator->errors()
      ], 422);
    }

    $token = JWTAuth::attempt($credentials);

    if ($token) {

      return response()->json([
        'token' => $token,
        'user' => User::where('email', $credentials['email'])->get()->first()
      ], 200);
    } else {
      return response()->json([
        'success' => false,
        'code' => 2,
        'message' => 'Wrong credentials',
        'errors' => $validator->errors()], 401);
    }
  }

  public function refreshToken()
  {

    $token = JWTAuth::getToken();

    try {
      $token = JWTAuth::refresh($token);
      return response()->json(['success' => true, 'token' => $token], 200);
    } catch (TokenExpiredException $ex) {
      // We were unable to refresh the token, our user needs to login again
      return response()->json([
        'code' => 3, 'success' => false, 'message' => 'Need to login again, please (expired)!'
      ]);
    } catch (TokenBlacklistedException $ex) {
      // Blacklisted token
      return response()->json([
        'code' => 4, 'success' => false, 'message' => 'Need to login again, please (blacklisted)!'
      ], 422);
    }

  }

  public function logout()
  {
    //  $this->validate($request, ['token' => 'required']);
    $token = JWTAuth::getToken();

    try {
      $token = JWTAuth::invalidate($token);
      return response()->json([
        'code' => 5, 'success' => true, 'message' => "You have successfully logged out."
      ], 200);
    } catch (JWTException $e) {
      return response()->json([
        'code' => 6, 'success' => false, 'message' => 'Failed to logout, please try again.'
      ], 422);
    }

  }

  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6|confirmed',
    ]);

    if($validator->fails()){
      return response()->json($validator->errors()->toJson(), 400);
    }

    $user = User::create([
      'name' => $request->get('name'),
      'email' => $request->get('email'),
      'password' => Hash::make($request->get('password')),
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json(compact('user','token'),201);
  }
}
