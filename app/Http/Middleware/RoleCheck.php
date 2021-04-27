<?php

namespace App\Http\Middleware;

use Closure,Auth;
use Illuminate\Http\Request;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        $user = Auth::user();
        if($user->role == $role){
            return $next($request);
        }else{
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }


       
    }
}
