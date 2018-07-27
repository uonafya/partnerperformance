<?php

namespace App\Http\Middleware;

use Closure;

class ClearSession
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
        session()->forget('filter_year');
        session()->forget('filter_month');
        session()->forget('to_year');
        session()->forget('to_month');
        
        session()->forget('filter_partner');

        session(['filter_year' => date('Y')]);

        return $next($request);
    }
}
