<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckMetamorphosisRaceSelect
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
    	if(Auth::check() && user()->getRace() == 0 && !$request->is('profile/select/race')) {
    		return redirect(url('/profile/select/race'));
		} else {
			return $next($request);
		}
    }
}
