<?php

namespace App;

use GuzzleHttp\Client;

use \App\County;
use \App\Subcounty;
use \App\Partner;
use \App\Ward;
use \App\Facility;

use \App\DataSet;
use \App\DataSetElement;


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

	public static function facilities()
	{
        $client = new Client(['base_uri' => self::$base]);
        $loop=true;
        $page=125;

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
        		$fac->facilitycode = $fac->facilitycode ?? $value->code ?? 0;
        		$fac->facilitycode = (int) $fac->facilitycode;

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

	public static function datasets()
	{
		$url = "dataSets.json?paging=false&filter=name:ilike:731&fields=id,name,code";
        $client = new Client(['base_uri' => self::$base]);

        $response = $client->request('get', $url, [
            'auth' => [env('DHIS_USERNAME'), env('DHIS_PASSWORD')],
            // 'http_errors' => false,
        ]);

        $body = json_decode($response->getBody());

        foreach ($body->dataSets as $key => $value) {
        	$d = new DataSet;
        	$d->name = $value->name ?? '';
        	$d->dhis = $value->id ?? '';
        	$d->code = $value->code ?? '';
        	$d->save();

        	$new_url = "dataSets/" . $d->dhis . ".json?fields=name,code,id,dataSetElements[dataElement[name,id,code],categoryCombo[id,name";

	        $elements_request = $client->request('get', $new_url, [
	            'auth' => [env('DHIS_USERNAME'), env('DHIS_PASSWORD')],
	            // 'http_errors' => false,
	        ]);

	        $elements = json_decode($elements_request->getBody());

	        foreach ($elements->dataSetElements as $element) {
	        	$e = new DataSetElement;
	        	$e->data_set_id = $d->id;
	        	$e->name = $element->dataElement->name ?? '';
	        	$e->code = $element->dataElement->code ?? '';
	        	$e->dhis = $element->dataElement->id ?? '';
	        	$e->save();

	        	$d->category_dhis = $element->categoryCombo->id ?? '';
	        }

	        $d->save();
        }


	}

	public static function stuff()
	{

		// https://hiskenya.org/api/analytics?dimension=dx:F9yzD1uwtqU;&dimension=ou:z2V9BrTObHC;mu9d9jNXA6Y;&dimension=pe:2018;&
		// dx is the dataset data datasetelement dataelement id
		// co is the dataset data datasetelement categorycombo id
		// ou is the facility dhis code
		// pe is the period
	}




}
