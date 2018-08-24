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
			->whereIn('county', $c)		
			->orderBy('name', 'asc')
			->orderBy('p_early_indicators.id', 'asc')
			->get();

		foreach ($rows as $key => $row) {
			$row_array = get_object_vars($row);
			$data[] = $row_array;
		}

		$filename = str_replace(' ', '_', strtolower($partner->name)) . '_' . $financial_year . '_early_warning_indicators';

    	$path = storage_path('exports/' . $filename . '.xlsx');
    	if(file_exists($path)) unlink($path);

    	Excel::create($filename, function($excel) use($data){
    		$excel->sheet('sheet1', function($sheet) use($data){
    			$sheet->fromArray($data);
    		});

    	})->store('xlsx');

    	return response()->download($path);
	}



	public function upload_excel(Request $request)
	{
		if (!$request->hasFile('upload')){
	        session(['toast_message' => 'Please select a file before clicking the submit button.']);
	        session(['toast_error' => 1]);
			return back();
		}
		$file = $request->upload->path();

		$data = Excel::load($file, function($reader){
			$reader->toArray();
		})->get();

		$partner = session('session_partner');

		// print_r($data);die();

		$today = date('Y-m-d');

		foreach ($data as $key => $value) {
			$update_data = [
				'tested' => (int) $value->tested ?? null,
				'positive' => (int) $value->positives ?? null,
				'new_art' => (int) $value->new_on_art ?? null,
				'linkage' => (double) $value->linkage_percentage ?? null,
				'current_tx' => (int) $value->current_on_art ?? null,
				'net_new_tx' => (int) $value->net_new_on_art ?? null,
				'vl_total' => (int) $value->vl_total ?? null,	
				'eligible_for_vl' => (int) $value->eligible_for_vl ?? null,	
				'pmtct' => (int) $value->pmtct ?? null,	
				'pmtct_stat' => (int) $value->pmtct_stat ?? null,	
				'pmtct_new_pos' => (int) $value->pmtct_new_positives ?? null,	
				'art_pmtct' => (int) $value->art_pmtct ?? null,	
				'art_uptake_pmtct' => (int) $value->art_uptake_pmtct ?? null,	
				'eid_lt_2m' => (int) $value->eid_less_2_months ?? null,	
				'eid_lt_12m' => (int) $value->eid_less_12_months ?? null,	
				'eid_total' => (int) $value->eid_total ?? null,	
				'eid_pos' => (int) $value->eid_positives ?? null,

				// 'tested' => $value->tested,
				// 'positive' => $value->positives,
				// 'new_art' => $value->new_on_art,
				// 'linkage' => $value->linkage_percentage,
				// 'current_tx' => $value->current_on_art,
				// 'net_new_tx' => $value->net_new_on_art,
				// 'vl_total' => $value->vl_total,	
				// 'eligible_for_vl' => $value->eligible_for_vl,	
				// 'pmtct' => $value->pmtct,	
				// 'pmtct_stat' => $value->pmtct_stat,	
				// 'pmtct_new_pos' => $value->pmtct_new_positives,	
				// 'art_pmtct' => $value->art_pmtct,	
				// 'art_uptake_pmtct' => $value->art_uptake_pmtct,	
				// 'eid_lt_2m' => $value->eid_less_2_months,	
				// 'eid_lt_12m' => $value->eid_less_12_months,	
				// 'eid_total' => $value->eid_total,	
				// 'eid_pos' => $value->eid_positives,
				'dateupdated' => $today,	
			];

			$county = DB::table('countys')->where('countymflcode', $value->county_mfl)->first();

			DB::connection('mysql_wr')->table('p_early_indicators')
				->where([
					'county' => $county->id, 'partner' => $partner->id, 
					'financial_year' => $value->financial_year, 'month' => $value->month
				])
				->update($update_data);
		}
		session(['toast_message' => 'The updates have been made.']);
		return back();

	}
}
