<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;

class IndicatorController extends Controller
{

	public function testing()
	{
		$date_query = Lookup::date_query();

		$rows = DB::table('p_early_indicators_view')
			->selectRaw("SUM(tested) as tests, SUM(positive) as pos")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		$testing_rows = DB::table('m_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_testing.facility')
			->selectRaw("SUM(testing_total) AS tests, SUM(positive_total) as pos")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		$pmtct_rows = DB::table('m_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_pmtct.facility')
			->selectRaw("SUM(tested_pmtct) AS tests, SUM(total_new_positive_pmtct) as pos")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		$sql2 = "
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";

		$target_obj = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->when(true, $this->target_callback())
			->get();

		$groupby = session('filter_groupby', 1);
		$divisor = Lookup::get_target_divisor();

		if($groupby > 9){
			$t = $target_obj->first()->tests;
			$target = round(($t / $divisor), 2);
		}

		$data['div'] = str_random(15);

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";
		$data['outcomes'][4]['type'] = "column";
		$data['outcomes'][5]['type'] = "column";
		$data['outcomes'][6]['type'] = "spline";

		// $data['outcomes'][0]['color'] = "#F2784B";
		// $data['outcomes'][1]['color'] = "#1BA39C";
		// $data['outcomes'][2]['color'] = "column";
		// $data['outcomes'][3]['color'] = "column";
		// $data['outcomes'][4]['color'] = "column";
		// $data['outcomes'][5]['color'] = "column";
		// $data['outcomes'][6]['color'] = "#ff4000";

		$data['outcomes'][0]['name'] = "Partner Reported Positive Tests";
		$data['outcomes'][1]['name'] = "Partner Reported Negative Tests";

		$data['outcomes'][2]['name'] = "DHIS Positive Tests";
		$data['outcomes'][3]['name'] = "DHIS Negative Tests";

		$data['outcomes'][4]['name'] = "DHIS Positive PMTCT";
		$data['outcomes'][5]['name'] = "DHIS Negative PMTCT";

		$data['outcomes'][6]['name'] = "Target";

		$data['outcomes'][0]['stack'] = 'datim';
		$data['outcomes'][1]['stack'] = 'datim';
		
		$data['outcomes'][2]['stack'] = 'dhis';
		$data['outcomes'][3]['stack'] = 'dhis';
		$data['outcomes'][4]['stack'] = 'dhis';
		$data['outcomes'][5]['stack'] = 'dhis';

		if($groupby < 10){
			$data['outcomes'][6]['lineWidth'] = 0;
			$data['outcomes'][6]['marker'] = ['enabled' => true, 'radius' => 4];
			$data['outcomes'][6]['states'] = ['hover' => ['lineWidthPlus' => 0]];
		}

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$testing = Lookup::get_val($row, $testing_rows, ['tests', 'pos']);
			$pmtct = Lookup::get_val($row, $pmtct_rows, ['tests', 'pos']);

			$data["outcomes"][0]["data"][$key] = (int) $row->pos;
			$data["outcomes"][1]["data"][$key] = (int) ($row->tests - $row->pos);

			$data["outcomes"][2]["data"][$key] = (int) $testing['pos'];
			$data["outcomes"][3]["data"][$key] = (int) ($testing['tests'] - $testing['pos']);

			$data["outcomes"][4]["data"][$key] = (int) $pmtct['pos'];
			$data["outcomes"][5]["data"][$key] = (int) ($pmtct['tests'] - $pmtct['pos']);

			if(isset($target)) $data["outcomes"][6]["data"][$key] = $target;
			else{
				$t = $target_obj->where('div_id', $row->div_id)->first()->tests ?? 0;
				$data["outcomes"][6]["data"][$key] = round(($t / $divisor), 2);
			}
		}		
		return view('charts.bar_graph', $data);
	}


	public function positivity()
	{
		$date_query = Lookup::date_query();

		$rows = DB::table('p_early_indicators_view')
			->selectRaw("SUM(tested) as tests, SUM(positive) as pos, SUM(pmtct_new_pos) as pmtct_new_pos, SUM(pmtct) as pmtct ")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		$testing_rows = DB::table('m_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_testing.facility')
			->selectRaw("SUM(testing_total) AS tests, SUM(positive_total) as pos")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		$pmtct_rows = DB::table('m_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_pmtct.facility')
			->selectRaw("SUM(tested_pmtct) AS tests, SUM(total_new_positive_pmtct) as pos")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		$sql2 = "
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";

		$target_obj = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->when(true, $this->target_callback())
			->get();

		$groupby = session('filter_groupby', 1);
		$divisor = Lookup::get_target_divisor();

		if($groupby > 9){
			$t_tests = $target_obj->first()->tests ?? 0;
			$t_pos = $target_obj->first()->pos ?? 0;
			// $target = round(($t / $divisor), 2);
			$target = Lookup::get_percentage($t_pos, $t_tests);
		}

		$data['div'] = str_random(15);

		$data['ytitle'] = 'Percentage';

		$data['paragraph'] = '<p>P.R. - Partner Reported </p>';

		$data['outcomes'][0]['name'] = "P.R. Testing Positivity";
		$data['outcomes'][1]['name'] = "P.R. PMTCT Positivity";
		$data['outcomes'][2]['name'] = "DHIS Testing Positivity";
		$data['outcomes'][3]['name'] = "DHIS PMTCT Positivity";
		$data['outcomes'][4]['name'] = "Targeted Positivity";

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			
			$data["outcomes"][0]["data"][$key] = Lookup::get_percentage($row->pos, $row->tests);
			$data["outcomes"][1]["data"][$key] = Lookup::get_percentage($row->pmtct_new_pos, $row->pmtct);

			$testing = Lookup::get_val($row, $testing_rows, ['tests', 'pos']);
			$pmtct = Lookup::get_val($row, $testing_rows, ['tests', 'pos']);

			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($testing['pos'], $testing['tests']);
			$data["outcomes"][3]["data"][$key] = Lookup::get_percentage($pmtct['pos'], $pmtct['tests']);

			if(isset($target)) $data["outcomes"][4]["data"][$key] = $target;
			else{				
				$obj = $target_obj->where('div_id', $row->div_id)->first();
				// $target_tests = round(($obj->tests / $divisor), 2);
				// $target_pos = round(($obj->pos / $divisor), 2);
				// $data["outcomes"][4]["data"][$key] = Lookup::get_percentage($obj->pos, $obj->tests);
				$target_tests = $obj->tests ?? 0;
				$target_pos = $obj->pos ?? 0;
				$data["outcomes"][4]["data"][$key] = Lookup::get_percentage($target_pos, $target_tests);
			}
		}		
		return view('charts.bar_graph', $data);
	}	

	public function currenttx()
	{
		$date_query = Lookup::date_query();
		$groupby = session('filter_groupby', 1);

		if($groupby != 12) $date_query = Lookup::year_month_query();

		$sql = "
			SUM(current_below1) AS below1,
			(SUM(current_below10) + SUM(current_below15_m) + SUM(current_below15_f)) AS below15,
			(SUM(current_below20_m) + SUM(current_below20_f) + SUM(current_below25_m) + SUM(current_below25_f) + SUM(current_above25_m) + SUM(current_above25_f)) AS above15
		";

		$rows = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback('above15'))
			->whereRaw($date_query)
			->get();

		$rows3 = DB::table('d_regimen_totals')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_regimen_totals.facility')
			->selectRaw("(SUM(d_regimen_totals.art) + SUM(pmtct)) AS total ")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->get();

		$early_rows = DB::table('p_early_indicators_view')
			->selectRaw("SUM(current_tx) as total ")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->get();

		$date_query = Lookup::date_query(true);
		$target_obj = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `total`")
			->when(true, $this->target_callback())
			->get();

		$groupby = session('filter_groupby', 1);
		// $divisor = Lookup::get_target_divisor();
		$divisor = 1;

		if($groupby > 9){
			$t = $target_obj->first()->total;
			$target = round(($t / $divisor), 2);
		}

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Below 1";
		$data['outcomes'][1]['name'] = "Below 15";
		$data['outcomes'][2]['name'] = "Above 15";
		$data['outcomes'][3]['name'] = "MOH 729 Current tx Total";
		$data['outcomes'][4]['name'] = "Partner Reported";
		$data['outcomes'][5]['name'] = "Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";
		$data['outcomes'][4]['type'] = "column";
		$data['outcomes'][5]['type'] = "spline";

		$data['outcomes'][0]['stack'] = 'current_art';
		$data['outcomes'][1]['stack'] = 'current_art';
		$data['outcomes'][2]['stack'] = 'current_art';
		$data['outcomes'][3]['stack'] = 'moh_729';
		$data['outcomes'][4]['stack'] = 'partner_reported';

		if($groupby < 10){
			$data['outcomes'][5]['lineWidth'] = 0;
			$data['outcomes'][5]['marker'] = ['enabled' => true, 'radius' => 4];
			$data['outcomes'][5]['states'] = ['hover' => ['lineWidthPlus' => 0]];
		}

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->below1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below15;
			$data["outcomes"][2]["data"][$key] = (int) $row->above15;

			$data["outcomes"][3]["data"][$key]  = (int) Lookup::get_val($row, $rows3, 'total');
			$data["outcomes"][4]["data"][$key]  = (int) Lookup::get_val($row, $early_rows, 'total');

			if(isset($target)) $data["outcomes"][5]["data"][$key] = $target;
			else{				
				$t = $target_obj->where('div_id', $row->div_id)->first()->total ?? 0;
				$data["outcomes"][5]["data"][$key] = round(($t / $divisor), 2);
			}
		}
		return view('charts.bar_graph', $data);
	}

	public function newtx()
	{
		$date_query = Lookup::date_query();
		$sql = "
			SUM(new_below1) AS below1,
			(SUM(new_below10) + SUM(new_below15_m) + SUM(new_below15_f)) AS below15,
			(SUM(new_below20_m) + SUM(new_below20_f) + SUM(new_below25_m) + SUM(new_below25_f) + SUM(new_above25_m) + SUM(new_above25_f)) AS above15
		";

		$rows = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback('above15'))
			->whereRaw($date_query)
			->get();

		$early_rows = DB::table('p_early_indicators_view')
			->selectRaw("SUM(new_art) as total ")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->get();

		$date_query = Lookup::date_query(true);
		$target_obj = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) AS `total`")
			->when(true, $this->target_callback())
			->get();

		$groupby = session('filter_groupby', 1);
		$divisor = Lookup::get_target_divisor();

		if($groupby > 9){
			$t = $target_obj->first()->total;
			$target = round(($t / $divisor), 2);
		}

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Below 1";
		$data['outcomes'][1]['name'] = "Below 15";
		$data['outcomes'][2]['name'] = "Above 15";
		$data['outcomes'][3]['name'] = "Partner Reported";
		$data['outcomes'][4]['name'] = "Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";
		$data['outcomes'][4]['type'] = "spline";

		$data['outcomes'][0]['stack'] = 'new_art';
		$data['outcomes'][1]['stack'] = 'new_art';
		$data['outcomes'][2]['stack'] = 'new_art';
		$data['outcomes'][3]['stack'] = 'partner_reported';

		if($groupby < 10){
			$data['outcomes'][4]['lineWidth'] = 0;
			$data['outcomes'][4]['marker'] = ['enabled' => true, 'radius' => 4];
			$data['outcomes'][4]['states'] = ['hover' => ['lineWidthPlus' => 0]];
		}

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->below1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below15;
			$data["outcomes"][2]["data"][$key] = (int) $row->above15;

			$data["outcomes"][3]["data"][$key] = (int) Lookup::get_val($row, $early_rows, 'total');

			if(isset($target)) $data["outcomes"][4]["data"][$key] = $target;
			else{				
				$t = $target_obj->where('div_id', $row->div_id)->first()->total ?? 0;
				$data["outcomes"][4]["data"][$key] = round(($t / $divisor), 2);
			}
		}
		return view('charts.bar_graph', $data);
	}

	public function summary()
	{
		$date_query = Lookup::date_query();
		$data = Lookup::table_data();

		$data['rows'] = DB::table('p_early_indicators_view')
			->selectRaw("SUM(tested) AS tests, SUM(positive) AS pos, SUM(new_art) AS new_art, SUM(net_new_tx) AS net_new_tx")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		$groupby = session('filter_groupby', 1);

		if($groupby != 12) $date_query = Lookup::year_month_query();

		$data['art'] = DB::table('p_early_indicators_view')
			->selectRaw("SUM(current_tx) AS current_tx")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->get();

		$data['current_tx_date'] =  Lookup::year_month_name();
		return view('tables.indicators_summary', $data);
	}








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
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}
		$data = [];

		$c = DB::table('view_facilitys')->select('county')->where('partner', $partner->id)->groupBy('county')->get()->pluck(['county'])->toArray();
		
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
			if($data[$key]['Linkage Percentage']){
				// $str = ($data[$key]['Linkage Percentage'] * 100) . "%";
				// $data[$key]['Linkage Percentage'] = str_replace("''", "", $str);

				$data[$key]['Linkage Percentage'] *= 100;
			}
		}

		$filename = str_replace(' ', '_', strtolower($partner->name)) . '_' . $financial_year . '_early_warning_indicators_monthly_data';

    	// $path = storage_path('exports/' . $filename);
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
		
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}

		// print_r($data);die();

		$today = date('Y-m-d');

		foreach ($data as $key => $value) {
			if(!isset($value->county_mfl)){
				session([
				'toast_message' => "This upload is incorrect. Please ensure that you are submitting on the right form.",
				'toast_error' => 1,
				]);
				return back();	
			}

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

			if(!$county) continue;

			DB::connection('mysql_wr')->table('p_early_indicators')
				->where([
					'county' => $county->id, 'partner' => auth()->user()->partner_id, 
					'financial_year' => $value->financial_year, 'month' => $value->month
				])
				->update($update_data);
		}
		session(['toast_message' => 'The updates have been made.']);
		return back();
	}	
}
