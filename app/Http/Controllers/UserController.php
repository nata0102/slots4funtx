<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
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
