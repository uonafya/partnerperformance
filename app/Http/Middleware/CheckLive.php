<?php

namespace App\Http\Middleware;

use Closure;

class CheckLive
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
        if(env('DB_HOST') == '10.231.111.73'){
            session(['toast_message' => 'Sorry, the service you are attempting to access is not currently available.']);
            session(['toast_error' => 1]);
            return redirect('/');
        }
        
        return $next($request);
    }
}
