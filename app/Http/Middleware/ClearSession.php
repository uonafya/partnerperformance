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
        session()->forget('filter_financial_year');
        session()->forget('filter_quarter');

        session()->forget('filter_year');
        session()->forget('filter_month');
        session()->forget('to_year');
        session()->forget('to_month');
        
        session()->forget('filter_county');
        session()->forget('filter_subcounty');
        session()->forget('filter_ward');
        session()->forget('filter_facility');
        session()->forget('filter_partner');
        session()->forget('filter_agency');

        session()->forget('filter_groupby');
        session()->forget('filter_pns_age');

        session()->forget('filter_week');
        session()->forget('filter_age');
        session()->forget('filter_gender');
        session()->forget('filter_modality');

        $m = date('m');

        if($m < 10){
            $f = date('Y');
        }else{
           $f = date('Y')+1; 
        }

        session([
            'financial' => true,
            'filter_financial_year' => $f,
            'filter_groupby' => 1,
            // 'filter_year' => date('Y'),
            // 'filter_financial_year' => date('Y'),
        ]);

        return $next($request);
    }
}
