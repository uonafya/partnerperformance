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

	        	if(!$sub){
	        		$sub = new Subcounty;
	        		$sub->SubCountyDHISCode = $value->id;
	        	}
        		$county = County::where('CountyDHISCode', $value->parent->id)->get()->first();
        		$sub->county = $county->id ?? 0;
        		$sub->name = $value->name;
        		$sub->save();
	        }

	        if($page == $body->pageCount) break;
	        echo  'Page ' . $page . ' completed';
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
	            'http_errors' => false,
	        ]);

	        $body = json_decode($response->getBody());

	        foreach ($body->organisationUnits as $key => $value) {
	        	$sub = Subcounty::where('SubCountyDHISCode', $value->id)->get()->first();

	        	if(!$sub){
	        		$sub = new Subcounty;
	        	}
        		$county = County::where('CountyDHISCode', $value->parent->id)->get()->first();
        		$sub->county = $county->id ?? 0;
        		$sub->name = $value->name;
        		$sub->save();

        		$ward = new Ward;
        		$ward->name = $value->name;
        		$ward->WardDHISCode = $value->id;
        		$ward->rawcode = $value->code;

				$sub = Subcounty::where('SubCountyDHISCode', $value->id)->get()->first();
				$ward->subcounty_id = $sub->id ?? 0;        		
	        }

	        if($page == $body->pageCount) break;
	        $page++;
        }

	}


}
