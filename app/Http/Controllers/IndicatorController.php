<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;
use App\Facility;
use App\ViewFacility;

class IndicatorController extends Controller
{

	public $raw = "
		countymflcode AS `County MFL`, name as `County`, 
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name`,
		tested AS `Tested`, positive AS `Positives`, new_art AS `New On ART`, linkage AS `Linkage Percentage`,
		current_tx AS `Current On ART`, net_new_tx AS `Net New On ART`, vl_total AS `VL Total`, 
		eligible_for_vl AS `Eligible For VL`,
		pmtct AS `PMTCT`, pmtct_stat AS `PMTCT STAT`, pmtct_new_pos AS `PMTCT New Positives`,
		pmtct_known_pos AS `PMTCT Known Positives`, pmtct_total_pos AS `PMTCT Total Positives`, 
		art_pmtct AS `ART PMTCT`, art_uptake_pmtct AS `ART Uptake PMTCT`,
		eid_lt_2m AS `EID Less 2 Months`, eid_lt_12m AS `EID Less 12 Months`,
		eid_total AS `EID Total`, eid_pos AS `EID Positives`
	";

	public function download_excel($financial_year)
	{
		$partner = session('session_partner');
		$data = [];

		$c = DB::table('view_facilitys')->where('partner', $partner->id)->groupBy('county')->get()->pluck(['county'])->toArray();

		
		$rows = DB::table('p_early_indicators')
			->join('countys', 'countys.id', '=', 'p_early_indicators.county')
			->selectRaw($this->raw)
			->when($financial_year, function($query) use ($financial_year){
				return $query->where('financial_year', $financial_year);
			})
			->where('partner', $partner->id)			
			->orderBy('name', 'asc')
			->orderBy('p_early_indicators.id', 'asc')
			->get();

		foreach ($rows as $key => $row) {
			$row_array = get_object_vars($row);
			$data[] = $row_array;
		}

		$filename = str_replace(' ', '_', strtolower($partner->name)) . '_' . $financial_year . 'early_warning_indicators';

    	// $path = storage_path('exports/' . $filename . '.xlsx');
    	// if(file_exists($path)) unlink($path);

    	Excel::create($filename, function($excel) use($data){
    		$excel->sheet('sheet1', function($sheet) use($data){
    			$sheet->fromArray($data);
    		});

    	})->store('xlsx');

    	return response()->download($path);
	}
}
