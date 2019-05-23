<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;

class CheckNascop
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
        if(env('DB_HOST') == '10.231.111.110'){
            $base = 'https://kemrinairobi.nascop.org:7443/api/';
            $client = new Client(['base_uri' => $base]); 
            $response = $client->request('get', 'hello_nascop', ['http_errors' => false, 'timeout' => 1]);
            if($response->getStatusCode() == 200) abort(500, 'NASCOP is back online. Please clear your cache then try again.');
        }
        
        return $next($request);
    }
}
