<?php

namespace App;

use GuzzleHttp\Client;

use \App\County;
use \App\Subcounty;
use \App\Partner;
use \App\Ward;
use \App\Facility;


class Synch 
{
	public static $base = 'https://hiskenya.org/api/';

	public static function subcounties(){

        $client = new Client(['base_uri' => self::$base]);
        $loop=true;
        $page=1;

        while($loop){

	        $response = $client->request('get', 'organisationUnits.json?paging=true&fields=id,name,code,parent[id,code,name]&filter=level:eq:3&page=' . $page, [
	            'auth' => [env('DHIS_USERNAME'), env('DHIS_PASSWORD')],
	            // 'http_errors' => false,
	        ]);

	        $body = json_decode($response->getBody());

	        foreach ($body->organisationUnits as $key => $value) {
	        	$sub = Subcounty::where('SubCountyDHISCode', $value->id)->get()->first();

	        	if(!$sub) $sub = new Subcounty;

        		$county = County::where('CountyDHISCode', $value->parent->id)->get()->first();
        		if($county && !$county->rawcode){
        			$county->rawcode = $value->parent->code;
        			$county->save();
        		}
        		$sub->county = $county->id ?? 0;
        		$sub->name = $value->name;
        		$sub->SubCountyDHISCode = $value->id;
        		$sub->save();
	        }

	        echo  'Page ' . $page . " completed \n";
	        if($page == $body->pager->pageCount) break;
	        $page++;
        }

	}

	public static function wards(){

        $client = new Client(['base_uri' => self::$base]);
        $loop=true;
        $page=1;

        while($loop){

	        $response = $client->request('get', 'organisationUnits.json?paging=true&fields=id,name,code,parent[id,code,name]&filter=level:eq:4&page=' . $page, [
	            'auth' => [env('DHIS_USERNAME'), env('DHIS_PASSWORD')],
	            // 'http_errors' => false,
	        ]);

	        $body = json_decode($response->getBody());

	        foreach ($body->organisationUnits as $key => $value) {
	        	$ward = Ward::where('WardDHISCode', $value->id)->get()->first();

	        	if(!$ward) $ward = new Ward;

        		$ward->name = $value->name;
        		$ward->WardDHISCode = $value->id;
        		$ward->rawcode = $value->code ?? null;

				$sub = Subcounty::where('SubCountyDHISCode', $value->parent->id)->get()->first();
				$ward->subcounty_id = $sub->id ?? 0;   
				$ward->save();     		
	        }

	        echo  'Page ' . $page . " completed \n";
	        if($page == $body->pager->pageCount) break;
	        $page++;
        }
	}

	public static function facilities(){

        $client = new Client(['base_uri' => self::$base]);
        $loop=true;
        $page=1;

        while($loop){

	        $response = $client->request('get', 'organisationUnits.json?paging=true&fields=id,name,code,parent[id,code,name]&filter=level:eq:5&page=' . $page, [
	            'auth' => [env('DHIS_USERNAME'), env('DHIS_PASSWORD')],
	            // 'http_errors' => false,
	        ]);

	        $body = json_decode($response->getBody());

	        foreach ($body->organisationUnits as $key => $value) {
	        	
	        	$mfl = $value->code ?? null;

	        	$fac = Facility::where('DHISCode', $value->id)
			        	->when($mfl, function($query) use ($value){
			        		return $query->orWhere('facilitycode', $value->code);
			        	})
			        	->get()->first();

	        	if(!$fac) $fac = new Facility;

        		$fac->new_name = $value->name;
        		$fac->DHISCode = $value->id;
        		$fac->facilitycode = $value->code ?? $fac->facilitycode ?? '';

        		$ward = Ward::where('WardDHISCode', $value->parent->id)->get()->first();
				$fac->ward_id = $ward->id ?? 0;        		
				$fac->subcounty_id = $ward->subcounty_id ?? 0;  

				$fac->save();
	        }

	        echo  'Page ' . $page . " completed \n";
	        if($page == $body->pager->pageCount) break;
	        $page++;
        }
	}




}
