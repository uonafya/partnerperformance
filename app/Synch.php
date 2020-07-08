<?php

namespace App;

use GuzzleHttp\Client;

use \App\County;
use \App\Subcounty;
use \App\Partner;
use \App\Ward;
use \App\Facility;

use \App\Lookup;
use \App\Period;

use \App\DataSet;
use \App\DataSetElement;

use DB;


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
	        	$sub = Subcounty::where('SubCountyDHISCode', 'like', "%{$value->id}%")->first();

	        	if(!$sub) $sub = new Subcounty;

        		$county = County::where('CountyDHISCode', $value->parent->id)->first();
        		if($county && !$county->rawcode){
        			$county->rawcode = $value->parent->code;
        			$county->save();
        		}
        		$sub->county = $county->id ?? 0;
        		$name = $value->name;

        		$name = str_replace('Sub', '', $name);
        		$name = str_replace('County', '', $name);
        		$name = str_replace('-', '', $name);
        		$name = trim($name);
        		$sub->name = $name;
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
	        	$ward = Ward::where('WardDHISCode', $value->id)->first();

	        	if(!$ward) $ward = new Ward;

        		$ward->name = $value->name;
        		$ward->WardDHISCode = $value->id;
        		$ward->rawcode = $value->code ?? null;

				$sub = Subcounty::where('SubCountyDHISCode', $value->parent->id)->first();
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
        $page=1;

        $dhis_facilities = [];

        while($loop){

	        $response = $client->request('get', 'organisationUnits.json?paging=true&fields=id,name,code,parent[id,code,name]&filter=level:eq:5&page=' . $page, [
	            'auth' => [env('DHIS_USERNAME'), env('DHIS_PASSWORD')],
	            // 'http_errors' => false,
	        ]);

	        $body = json_decode($response->getBody());

	        foreach ($body->organisationUnits as $key => $value) {

	        	$mfl = $value->code ?? null;
	        	$mfl = (int) $mfl;

	        	$facilities = Facility::where('DHIScode', $value->id)
			        	->when($mfl, function($query) use ($value){
			        		return $query->orWhere('facilitycode', $value->code);
			        	})->get();

			    if($facilities->count() == 1) $fac = $facilities->first();
			    else if($facilities->count() == 0)  $fac = new Facility;
			    else{

			    	// $fac = $facilities->where('DHIScode', '!=', '0')->first();

			    	$clashing_ids = $clashing_mfl = $clashing_dhis = '';

			    	foreach ($facilities as $f) {
			    		$clashing_ids .= $f->id . ',';
			    		$clashing_mfl .= $f->facilitycode . ',';
			    		$clashing_dhis .= $f->DHIScode . ',';
			    	}

			    	$dhis_facilities[] = [
			    		'facility_dhis' => $value->id,
			    		'facility_mfl' => $value->code ?? null,
			    		'facility' => $value->name,
			    		'ward_dhis' => $value->parent->id ?? null,
			    		'ward' => $value->parent->name ?? null,
			    		// 'subcounty_dhis' => $value->parent->parent->id ?? null,
			    		// 'subcounty_mfl' => $value->parent->parent->code ?? null,
			    		// 'subcounty' => $value->parent->parent->name ?? null,
			    		'clashing_ids' => $clashing_ids,
			    		'clashing_mfl' => $clashing_mfl,
			    		'clashing_dhis' => $clashing_dhis,
			    	];
			    	continue;	
			    }

	        	if(!$fac) $fac = new Facility;

        		$fac->new_name = $value->name;
        		$fac->DHIScode = $value->id;
        		$fac->facilitycode = $fac->facilitycode ?? $value->code ?? 0;
        		$fac->facilitycode = (int) $fac->facilitycode;

        		$ward = Ward::where('WardDHISCode', $value->parent->id)->first();
				$fac->ward_id = $ward->id ?? 0;        		
				$fac->subcounty_id = $ward->subcounty_id ?? $fac->district ?? 0;  

				$fac->save();
	        }

	        echo  'Page ' . $page . " completed \n";
	        if($page == $body->pager->pageCount) break;
	        $page++;
        }
        Lookup::print_duplicates($dhis_facilities);
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

        	$table_name = 'd_' . self::table_name_formatter($d->name);
        	$targets_table_name = 't_' . self::table_name_formatter($d->name);

        	$sql = "CREATE TABLE `{$table_name}` (
        				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        				facility int(10) UNSIGNED DEFAULT 0,
        				year smallint(4) UNSIGNED DEFAULT 0,
        				month tinyint(3) UNSIGNED DEFAULT 0,
        				financial_year smallint(4) UNSIGNED DEFAULT 0,
        				quarter tinyint(3) UNSIGNED DEFAULT 0,
        	";

        	$sql2 = "CREATE TABLE `{$targets_table_name}` (
        				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        				facility int(10) UNSIGNED DEFAULT 0,
        				financial_year smallint(4) UNSIGNED DEFAULT 0,
        	";

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

	        	$column_name = self::column_name_formatter($e->name);

	        	$e->table_name = $table_name;
	        	$e->targets_table_name = $targets_table_name;
	        	$e->column_name = $column_name;
	        	$e->save();

	        	$sql .= "
	        	`{$column_name}` int(10) DEFAULT NULL, ";

	        	$sql2 .= "
	        	`{$column_name}` int(10) DEFAULT NULL, ";

	        	$d->category_dhis = $element->categoryCombo->id ?? '';
	        }

	        DB::connection('mysql_wr')->statement("DROP TABLE IF EXISTS `{$table_name}`;");
	        DB::connection('mysql_wr')->statement("DROP TABLE IF EXISTS `{$targets_table_name}`;");

	        $sql .= "
	        		dateupdated date DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `identifier`(`facility`, `year`, `month`),
					KEY `identifier_other`(`facility`, `financial_year`, `quarter`),
					KEY `facility` (`facility`),
					KEY `specific_time` (`year`, `month`),
					KEY `specific_period` (`financial_year`, `quarter`)
				);
	        ";

	        $sql2 .= "
	        		dateupdated date DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `identifier`(`facility`, `financial_year`),
					KEY `facility` (`facility`)
				);
	        ";

	        DB::connection('mysql_wr')->statement($sql);
	        DB::connection('mysql_wr')->statement($sql2);
	        $d->save();
	        echo  'Data set ' . ($key+1) . " completed \n";
        }
	}


	public static function insert_rows($year=null)
	{
		if(!$year) $year = date('Y');
		$tables = DataSetElement::selectRaw("distinct table_name")->get();
		$facilities = Facility::select('id')->get();

        $periods = Period::where(['year' => $year])->get();

		foreach ($tables as $table) {

			$i=0;
			$data_array = [];

			for ($month=1; $month < 13; $month++) { 
				foreach ($facilities as $k => $fac) {
                    $data_array[$i] = ['period_id' => $period->id, 'facility' => $fac->id];
					$i++;

					if ($i == 200) {
						DB::connection('mysql_wr')->table($table->table_name)->insert($data_array);
						$data_array=null;
				    	$i=0;
					}
				}
			}
			if($data_array) DB::connection('mysql_wr')->table($table->table_name)->insert($data_array);

	        echo  'Completed entry for ' . $table->table_name . " \n";
		}

		$data_array=null;
    	$i=0;

		$tables = DataSetElement::selectRaw("distinct targets_table_name")->get();
		$facilities = Facility::select('id')->get();

		foreach ($tables as $table) {

			$i=0;
			$data_array = [];

			foreach ($facilities as $k => $val) {
				$data_array[$i] = array('financial_year' => $year, 'facility' => $val->id);
				$i++;

				if ($i == 200) {
					DB::connection('mysql_wr')->table($table->targets_table_name)->insert($data_array);
					$data_array=null;
			    	$i=0;
				}
			}

			if($data_array) DB::connection('mysql_wr')->table($table->targets_table_name)->insert($data_array);

	        echo 'Completed entry for ' . $table->targets_table_name . " \n";
		}
	}

	public static function truncate_tables()
	{
		$tables = DataSetElement::selectRaw("distinct table_name")->get();

		foreach ($tables as $table){
			DB::connection('mysql_wr')->statement("TRUNCATE TABLE `" . $table->table_name . "`;");
		}

		$tables = DataSetElement::selectRaw("distinct targets_table_name")->get();

		foreach ($tables as $table){
			DB::connection('mysql_wr')->statement("TRUNCATE TABLE `" . $table->targets_table_name . "`;");
		}
	}

	public static function populate($year=null)
	{
		if(!$year) $year = date('Y');
        $client = new Client(['base_uri' => self::$base]);
		$datasets = DataSet::with(['element'])->get();
		$periods = Period::where(['year' => $year])->get();

		echo 'Begin updates at ' . date('Y-m-d H:i:s a') . " \n";

		$pe='';
		$offset=0;

		for($month=1; $month < 13; $month++) {
			if($month < 10) $month = '0' . $month;
			$pe .= $year . $month . ';';
		}

		// Begin loop to get facilities
		while(true){

			$facilities = Facility::eligible($offset)->get();
			if($facilities->isEmpty()) break;
			$ou = '';

			// Put facilities' DHIS codes into a string
			foreach ($facilities as $facility) {
				$ou .= $facility->DHIScode . ';';
			}

			// Iterate through the data sets
			foreach ($datasets as $dataset) {
				$dx = '';
				foreach ($dataset->element as $element) {
					$dx .= $element->dhis . ';';
				}
				if(!$dx) continue;
				$co = $dataset->category_dhis;

				// $url = "analytics?dimension=dx:" . $dx . "&dimension=ou:" . $ou . "&dimension=co:" . $co . "&dimension=pe:" . $pe;
				// If co is set, it will be value[1]
	        	// https://hiskenya.org/api/dataValueSets.json?dataSet=​ptIUGFkE6jn​&period=​201806​&orgUnit=​HfVjCurKxh2


				$url = "analytics?dimension=dx:" . $dx . "&dimension=ou:" . $ou . "&dimension=pe:" . $pe;

		        $response = $client->request('get', $url, [
		            'auth' => [env('DHIS_USERNAME'), env('DHIS_PASSWORD')],
		            // 'http_errors' => false,
		        ]);

		        $body = json_decode($response->getBody());

		        foreach ($body->rows as $key => $value) {
		        	$elem = $dataset->element->where('dhis', $value[0])->first();
		        	$fac = $facilities->where('DHIScode', $value[1])->first();
		        	$period = str_split($value[2], 4);
		        	$y = $period[0];
		        	$m = $period[1];

		        	if(!$elem->table_name || !$elem->column_name) continue;
		        	$p = $periods->where('year', $y)->where('month', $m)->first();
		        	if(!$p) continue;


		        	DB::connection('mysql_wr')->table($elem->table_name)
		        		->where(['facility' => $fac->id, 'period_id' => $p->id, ])
		        		->update([$elem->column_name => $value[3], 'dateupdated' => date('Y-m-d')]);

		        	// echo "Updated {$elem->table_name} {$elem->column_name} {$fac->id} {$y} {$m} {$value[3]} \n ";
		        }
			}			
			$offset += 50;
	        echo  'Completed updates for ' . $offset . " facilities at " . date('Y-m-d H:i:s a') . " \n";
		}
		echo "Completed updates at " . date('Y-m-d H:i:s a') . " \n";
	} 

	public static function populate_regimen($year=null)
	{
		if(!$year) $year = date('Y');
        $client = new Client(['base_uri' => self::$base]);

        $regimens = DB::table('view_regimen_dhis')->get();
        $dmap_regimens = DB::table('view_dmap_regimen_dhis')->get();
        $services = DB::table('tbl_service')->get();

		$periods = Period::where(['year' => $year])->get();

        $messy_facilities = [];

        echo 'Begin updates at ' . date('Y-m-d H:i:s a') . " \n";

		$pe = $dx = $dmap_dx =  '';
		$offset=0;
		$dhis_periods = [];
		$my_services = [];

		for($month=1; $month < 13; $month++) {
			if($month < 10) $month = '0' . $month;
			$pe .= $year . $month . ';';
			$dhis_periods[] = [
				'name' => $year . $month, 
				'year' => $year,
				'month' => $month,
			];
		}

		foreach ($regimens as $regimen) {
			$dx .= $regimen->dhis_code . ';';
		}

		foreach ($dmap_regimens as $regimen) {
			$dmap_dx .= $regimen->dhis_code . ';';
		}

        foreach ($services as $service) {
        	$codes = $regimens->where('service_id', $service->id)->pluck('dhis_code')->toArray();
        	$dmap_codes = $dmap_regimens->where('service_id', $service->id)->pluck('dhis_code')->toArray();
        	$my_services[] = [
        		'service_id' => $service->id,
        		'column_name' => $service->column_name,
        		'dmap_column_name' => 'dmap_' . $service->column_name,
        		'codes' => $codes,	
        		'dmap_codes' => $dmap_codes,	
        	];
        }

		while (true) {
			$facilities = Facility::eligible($offset)->get();
			$offset += 50;
			if($facilities->isEmpty()) break;

			foreach ($facilities as $facility) {
				$ou = $facility->DHIScode . ';';
				// $url = "analytics?dimension=dx:" . $dx . "co:OzshuDqmXQI;" . "&dimension=ou:" . $ou . "&dimension=pe:" . $pe;
				$url = "analytics?dimension=dx:" . $dx . "&dimension=ou:" . $ou . "&dimension=pe:" . $pe;

				$response = $client->request('get', $url, [
		            'auth' => [env('DHIS_USERNAME'), env('DHIS_PASSWORD')],
		            'http_errors' => false,
		        ]);

		        if($response->getStatusCode() == 409){
		        	// dd($response->getError());
		        	// $messy_facilities[] = $facility->id;
		        	$facility->invalid_dhis = 1;
		        	$facility->save();
		        	continue;
		        }

		        $body = json_decode($response->getBody());

		        $other_fac = DB::table('tbl_facility')
		        	->where('mflcode', $facility->facilitycode)
		        	->orWhere('dhiscode', $facility->DHIScode)->first();

		        $dmap = false;
		        if($other_fac && $other_fac->category == "central") $dmap = true;

		        if($dmap){
					$url = "analytics?dimension=dx:" . $dmap_dx . "&dimension=ou:" . $ou . "&dimension=pe:" . $pe;

					$response = $client->request('get', $url, [
			            'auth' => [env('DHIS_USERNAME'), env('DHIS_PASSWORD')],
			            'http_errors' => false,
			        ]);
			        $dmap_body = json_decode($response->getBody());
		        }


		        foreach ($dhis_periods as $period) {
		        	$data['dateupdated'] = date('Y-m-d');
		        	foreach ($my_services as $my_service) {
		        		$column = $my_service['column_name'];
		        		$data[$column] = 0;

		        		if(!$body->rows) continue;

		        		foreach ($body->rows as $key => $value){
		        			if($value[2] == $period['name'] && in_array($value[0], $my_service['codes'])) {
		        				$data[$column] += $value[3];
		        			}
		        		}
		        		// if($dmap){
			        	// 	$column = $my_service['dmap_column_name'];
			        	// 	$data[$column] = 0;

			        	// 	if($dmap_body){
				        // 		foreach ($dmap_body->rows as $key => $value){
				        // 			if($value[2] == $period['name'] && in_array($value[0], $my_service['dmap_codes'])) {
				        // 				$data[$column] += $value[3];
				        // 			}
				        // 		}	
			        	// 	}	        			
		        		// }
		        		// else{
		        		// 	if($other_fac && $other_fac->category == "standalone"){
		        		// 		$dmap_column = $my_service['dmap_column_name'];
		        		// 		$data[$dmap_column] = $data[$column];
		        		// 	} 
		        		// }
		        	}

		        	$p = $periods->where('year', $period['year'])->where('month', $period['month'])->first();
		        	if(!$p) continue;

		        	DB::connection('mysql_wr')->table('d_regimen_totals')
		        		->where(['facility' => $facility->id, 'period_id' => $p->id])
		        		->update($data);
		        }
			}
			// echo 'Completed updates for ' . $offset . " facilities at " . date('Y-m-d H:i:s a') . " \n";
		}

		echo "Completed regimen updates at " . date('Y-m-d H:i:s a') . " \n";

		// DB::connection('mysql_wr')->whereIn('id', $messy_facilities)->update(['invalid_dhis' => 1]);
	}

	public static function stuff()
	{
		// https://hiskenya.org/api/analytics?dimension=dx:F9yzD1uwtqU;&dimension=ou:z2V9BrTObHC;mu9d9jNXA6Y;&dimension=pe:2018;&
		// dx is the dataset data datasetelement dataelement id
		// co is the dataset data datasetelement categorycombo id
		// ou is the facility dhis code
		// pe is the period
	}


	public static function table_name_formatter($raw)
	{
		$raw = strtolower($raw);
		$str = explode(' ', $raw);

		$size = sizeof($str);
		$final = '';

		for ($i=2; $i < $size; $i++) { 
			$final .= $str[$i] . '_';
		}
		$final = str_replace('revision_2018', '', $final);

		if(ends_with($final, '_')) $final = str_replace_last('_', '', $final);
		if(ends_with($final, '_')) $final = str_replace_last('_', '', $final);
		if(ends_with($final, '_')) $final = str_replace_last('_', '', $final);
		return $final;
	}

	public static function column_name_formatter($raw)
	{
		$raw = strtolower($raw);
		$raw = str_replace('moh 731', '', $raw);
		$raw = str_replace('moh731b', '', $raw);
		$raw = str_replace('.', '', $raw);
		$raw = str_replace('+', 'pos', $raw);

		$raw = str_replace(' ', '_', $raw);
		$raw = str_replace('__', '_', $raw);
		$raw = str_replace('__', '_', $raw);
		$raw = str_replace('__', '_', $raw);
		$raw = str_replace('__', '_', $raw);
		$raw = str_replace('__', '_', $raw);
		$raw = str_replace('__', '_', $raw);
		$raw = str_replace('(couples_only)', '', $raw);

		$raw = str_replace('number_started_on', '', $raw);

		$final = $raw;

		if(\Str::startsWith($final, '_')) $final = str_replace_first('_', '', $final);
		if(\Str::startsWith($final, '_')) $final = str_replace_first('_', '', $final);
		if(ends_with($final, '_')) $final = str_replace_last('_', '', $final);
		if(ends_with($final, '_')) $final = str_replace_last('_', '', $final);

		$length = strlen($final);

		if($length > 60) $final = str_limit($final, 60, '');

		if($final == 'linked_to_community_based_services') $final = $final . rand(1, 5);

		return $final;
	}

	public static function get_financial_year_quarter($year, $month)
	{
		if($month < 10) $financial_year = $year;
		else{
			$financial_year = $year + 1;
		}

		if($month < 4) $quarter = 2;
		else if($month > 3 && $month < 7) $quarter = 3;
		else if($month > 6 && $month < 10) $quarter = 4;
		else if($month > 9) $quarter = 1;

		return ['financial_year' => $financial_year, 'quarter' => $quarter];
	}




}
