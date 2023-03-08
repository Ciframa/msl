<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
   public function handle($request, Closure $next, $guard = null)
    {
        $token = ["remember_token" => $request->header('Authorization')];
    
        if((User::where('remember_token', '=', $token)->first()) === null){
              return response('Unauthorized.', 401);
        }
        return $next($request);
 
}}
