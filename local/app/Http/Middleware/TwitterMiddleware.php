<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class TwitterMiddleware
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
		if (!Auth::check()) echo '1'; //return redirect('auth/twitter');
        else echo '2';//return $next($request);
    }
}
