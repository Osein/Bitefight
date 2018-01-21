<?php

namespace App\Http\Middleware;

use Closure;

class CheckGameRoutine
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
		view()->share('user_new_message_count', 0);
		view()->share('clan_application_count', 0);

		return $next($request);
    }
}
