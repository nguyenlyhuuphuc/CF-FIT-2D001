<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAge18
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        
        $user = Auth::user();

        $dob = Carbon::createFromFormat('Y-m-d H:i:s', $user->dob);
        
        $age = floor($dob->diffInYears(Carbon::now()));

        if($age < 18){
            return abort(403);
        }

        return $next($request);
    }
}
