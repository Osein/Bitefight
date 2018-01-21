<?php

namespace App\Http\Middleware;

use Closure;

class SaveUserMiddleware
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
		$ret = $next($request);

		user()->save();

        return $ret;
    }
}
