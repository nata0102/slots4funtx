<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if (Auth::check()){
        $user = Auth::user()->id;
        $user = User::with('role')->first();
        if ($user->role->key_value == 'administrator')
          return $next($request);
      }

      return redirect('/');

    }
}
