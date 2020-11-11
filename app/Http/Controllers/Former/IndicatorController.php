<?php

namespace App\Http\Controllers\Former;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;

class IndicatorController extends Controller
{


	public function testing($positivity=false)
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$data['div'] = str_random(15);

		$rows = DB::table('p_early_indicators')
			->join('countys', 'countys.id', '=', 'p_early_indicators.county')
			->join('partners', 'partners.id', '=', 'p_early_indicators.partner')
			->selectRaw("SUM(tested) as tests, SUM(positive) as pos")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();


		$sql = "
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests,
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos
		";

		$dhis = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilities', 'view_facilities.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$sql2 = "
			SUM(`total_tested_hiv`) AS tests,
			SUM(`total_received_hivpos_results`) AS pos
		";

		$dhis_old = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilities', 'view_facilities.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($sql2)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$prows = DB::table('d_prevention_of_mother-to-child_transmission')
			->join('view_facilities', 'view_facilities.id', '=', 'd_prevention_of_mother-to-child_transmission.facility')
			->selectRaw("(SUM(`initial_test_at_anc_hv02-04`) + SUM(`initial_test_at_l&d_hv02-05`) + 	SUM(`initial_test_at_pnc_pnc<=6wks_hv02-06`)) AS `tests`, 
				(SUM(`total_positive_(add_hv02-10_-_hv02-14)_hv02-15`) - SUM(`known_positive_at_1st_anc_hv02-10`)) AS `pos`
			 ")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$psql = "SUM(`total_tested_(pmtct)`) AS `tests`, SUM(`total_positive_(pmtct)`) AS `pos` ";

		$prows2 = DB::table('d_pmtct')
			->join('view_facilities', 'view_facilities.id', '=', 'd_pmtct.facility')
			->selectRaw($psql)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();



		
		$date_query = Lookup::date_query(true);

		$target_obj = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilities', 'view_facilities.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->first();

		$target = round(($target_obj->tests / 12), 2);

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

		$old_table = "`d_hiv_counselling_and_testing`";
		$new_table = "`d_hiv_testing_and_prevention_services`";

		$old_column = "`total_received_hivpos_results`";
		$new_column = "`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`";

		$old_column_tests = "`total_tested_hiv`";
		$new_column_tests = "`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`";


		$pold_table = "`d_pmtct`";
		$pnew_table = "`d_prevention_of_mother-to-child_transmission`";

		$pnew_column = "`initial_test_at_anc_hv02-04`";


		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);

			$data["outcomes"][0]["data"][$key] = (int) $row->pos;
			$data["outcomes"][1]["data"][$key] = (int) ($row->tests - $row->pos);

			$duplicate_tests = DB::select(
				DB::raw("CALL `proc_get_duplicate_total_multiple`('{$old_table}', '{$new_table}', '{$sql2}', '{$new_column_tests}', '{$divisions_query}', {$row->year}, {$row->month});"));

			$tests = $dhis[$key]->tests + $dhis_old[$key]->tests - ($duplicate_tests[0]->tests ?? 0);
			$pos = $dhis[$key]->pos + $dhis_old[$key]->pos - ($duplicate_tests[0]->pos ?? 0);
			// $neg = $tests - $pos;

			$data["outcomes"][2]["data"][$key] = (int) $pos;
			$data["outcomes"][3]["data"][$key] = (int) ($tests - $pos);

			$duplicate_pmtct = DB::select(
				DB::raw("CALL `proc_get_duplicate_total_multiple`('{$pold_table}', '{$pnew_table}', '{$psql}', '{$pnew_column}', '{$divisions_query}', {$row->year}, {$row->month});"));

			$tests = $prows[$key]->tests + $prows2[$key]->tests - ($duplicate_pmtct[0]->tests ?? 0);
			$pos = $prows[$key]->pos + $prows2[$key]->pos - ($duplicate_pmtct[0]->pos ?? 0);

			$data["outcomes"][4]["data"][$key] = (int) $pos;
			$data["outcomes"][5]["data"][$key] = (int) ($tests - $pos);

			$data["outcomes"][6]["data"][$key] = $target;
		}

		return view('charts.bar_graph', $data);
	}



	public function positivity()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$data['div'] = str_random(15);

		$rows = DB::table('p_early_indicators')
			->join('countys', 'countys.id', '=', 'p_early_indicators.county')
			->join('partners', 'partners.id', '=', 'p_early_indicators.partner')
			->selectRaw("SUM(tested) as tests, SUM(positive) as pos")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();


		$sql = "
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests,
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos
		";

		$dhis = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilities', 'view_facilities.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$sql2 = "
			SUM(`total_tested_hiv`) AS tests,
			SUM(`total_received_hivpos_results`) AS pos
		";

		$dhis_old = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilities', 'view_facilities.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($sql2)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();
		
		$date_query = Lookup::date_query(true);

		$target_obj = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilities', 'view_facilities.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->first();

		$target = Lookup::get_percentage($target_obj->pos, $target_obj->tests);

		$data['yAxis'] = 'Percentage';

		$data['outcomes'][0]['name'] = "Partner Reported Positivity";
		$data['outcomes'][1]['name'] = "DHIS Positivity";
		$data['outcomes'][2]['name'] = "Targeted Positivity";


		$old_table = "`d_hiv_counselling_and_testing`";
		$new_table = "`d_hiv_testing_and_prevention_services`";

		$old_column = "`total_received_hivpos_results`";
		$new_column = "`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`";

		$old_column_tests = "`total_tested_hiv`";
		$new_column_tests = "`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`";


		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);

			$data["outcomes"][0]["data"][$key] = Lookup::get_percentage($row->pos, $row->tests);

			$duplicate_pos = DB::select(
				DB::raw("CALL `proc_get_duplicate_total`('{$old_table}', '{$new_table}', '{$old_column}', '{$new_column}', '{$divisions_query}', {$row->year}, {$row->month});"));

			$duplicate_tests = DB::select(
				DB::raw("CALL `proc_get_duplicate_total`('{$old_table}', '{$new_table}', '{$old_column_tests}', '{$new_column_tests}', '{$divisions_query}', {$row->year}, {$row->month});"));

			$tests = $dhis[$key]->tests + $dhis_old[$key]->tests - ($duplicate_tests[0]->total ?? 0);
			$pos = $dhis[$key]->pos + $dhis_old[$key]->pos - ($duplicate_pos[0]->total ?? 0);

			$data["outcomes"][1]["data"][$key] = Lookup::get_percentage($pos, $tests);

			$data["outcomes"][2]["data"][$key] = $target;
		}
		return view('charts.bar_graph', $data);		
	}


	public function currenttx()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilities', 'view_facilities.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw($this->current_art_query())
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$rows2 = DB::table('d_care_and_treatment')
			->join('view_facilities', 'view_facilities.id', '=', 'd_care_and_treatment.facility')
			->selectRaw($this->former_age_current_query())
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();


		$rows3 = DB::table('d_regimen_totals')
			->join('view_facilities', 'view_facilities.id', '=', 'd_regimen_totals.facility')
			->selectRaw("(SUM(d_regimen_totals.art) + SUM(pmtct)) AS total ")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$early_rows = DB::table('p_early_indicators')
			->join('countys', 'countys.id', '=', 'p_early_indicators.county')
			->join('partners', 'partners.id', '=', 'p_early_indicators.partner')
			->selectRaw("SUM(current_tx) as total")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$date_query = Lookup::date_query(true);
		$target = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilities', 'view_facilities.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `total`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->first();

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

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->below_1 + $rows2[$key]->below_1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below_10 + $row->below_15 + $rows2[$key]->below_15;
			$data["outcomes"][2]["data"][$key] = (int) $row->below_20 + $row->below_25 + $row->above_25 + $rows2[$key]->above_15;
			$data["outcomes"][3]["data"][$key] = (int) $rows3[$key]->total;
			$data["outcomes"][4]["data"][$key] = (int) $early_rows[$key]->total;

			$duplicate2 = DB::table('d_hiv_and_tb_treatment')
							->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
							->selectRaw($this->current_art_query())
							->whereRaw("`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0")
							->where(['year' => $row->year, 'month' => $row->month])
							->whereRaw("facility IN (
								SELECT DISTINCT facility
								FROM d_care_and_treatment d JOIN view_facilitys f ON d.facility=f.id
								WHERE  {$divisions_query} AND `total_currently_on_art` > 0 AND 
								year = {$row->year} AND month = {$row->month}
							)")
							->first();

			if(is_object($duplicate2)){
				$data["outcomes"][0]["data"][$key] -= $duplicate2->below_1;
				$data["outcomes"][1]["data"][$key] -= ($duplicate2->below_10 + $duplicate2->below_15);
				$data["outcomes"][2]["data"][$key] -= ($duplicate2->below_20 + $duplicate2->below_25 + $duplicate2->above_25);
			}

			$data["outcomes"][5]["data"][$key] = (int) $target->total;
		}
		return view('charts.bar_graph', $data);
	}


	public function newtx()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilities', 'view_facilities.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw($this->new_art_query())
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$rows2 = DB::table('d_care_and_treatment')
			->join('view_facilities', 'view_facilities.id', '=', 'd_care_and_treatment.facility')
			->selectRaw($this->former_new_art_query())
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$early_rows = DB::table('p_early_indicators')
			->join('countys', 'countys.id', '=', 'p_early_indicators.county')
			->join('partners', 'partners.id', '=', 'p_early_indicators.partner')
			->selectRaw("SUM(new_art) as total")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();


		$date_query = Lookup::date_query(true);
		$target = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilities', 'view_facilities.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) AS `total`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->first();

		$t = round(($target->total / 12), 2);

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Below 1";
		$data['outcomes'][1]['name'] = "Below 15";
		$data['outcomes'][2]['name'] = "Above 15";
		$data['outcomes'][3]['name'] = "Partner Reported";
		$data['outcomes'][4]['name'] = "Monthly Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";
		$data['outcomes'][4]['type'] = "spline";

		$data['outcomes'][0]['stack'] = 'new_art';
		$data['outcomes'][1]['stack'] = 'new_art';
		$data['outcomes'][2]['stack'] = 'new_art';
		$data['outcomes'][3]['stack'] = 'partner_reported';

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->below_1 + $rows2[$key]->below_1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below_10 + $row->below_15 + $rows2[$key]->below_15;
			$data["outcomes"][2]["data"][$key] = (int) $row->below_20 + $row->below_25 + $row->above_25 + $rows2[$key]->above_15;

			$duplicate2 = DB::table('d_hiv_and_tb_treatment')
							->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
							->selectRaw($this->new_art_query())
							->whereRaw("`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0")
							->where(['year' => $row->year, 'month' => $row->month])
							->whereRaw("facility IN (
								SELECT DISTINCT facility
								FROM d_care_and_treatment d JOIN view_facilitys f ON d.facility=f.id
								WHERE  {$divisions_query} AND `total_starting_on_art` > 0 AND 
								year = {$row->year} AND month = {$row->month}
							)")
							->first();

			if(is_object($duplicate2)){
				$data["outcomes"][0]["data"][$key] -= $duplicate2->below_1;
				$data["outcomes"][1]["data"][$key] -= ($duplicate2->below_10 + $duplicate2->below_15);
				$data["outcomes"][2]["data"][$key] -= ($duplicate2->below_20 + $duplicate2->below_25 + $duplicate2->above_25);
			}

			$data["outcomes"][3]["data"][$key] = (int) $early_rows[$key]->total;

			$data["outcomes"][4]["data"][$key] = $t;
		}
		return view('charts.bar_graph', $data);		
	}

	public function summary()
	{		
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$data['div'] = str_random(15);

		$sql = $q['select_query'] . ", SUM(tested) AS tests, SUM(positive) AS pos, SUM(new_art) AS new_art, SUM(net_new_tx) AS net_new_tx";

		$data['rows'] = DB::table('p_early_indicators_view')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$date_query = Lookup::year_month_query();

		$sql = $q['select_query'] . ", SUM(current_tx) AS current_tx ";

		$data['art'] = DB::table('p_early_indicators_view')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['current_tx_date'] =  '(' . session('tx_financial_year') . ', ' . Lookup::resolve_month(session('tx_month')) . ')';

		$date_query = Lookup::year_month_query(1);

		$data['art_recent'] = DB::table('p_early_indicators_view')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['recent_current_tx_date'] =  '(' . session('tx_financial_year') . ', ' . Lookup::resolve_month(session('tx_month')) . ')';
		return view('combined.indicators_summary', $data);
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
			if($data[$key]['Linkage Percentage']){
				// $str = ($data[$key]['Linkage Percentage'] * 100) . "%";
				// $data[$key]['Linkage Percentage'] = str_replace("''", "", $str);

				$data[$key]['Linkage Percentage'] *= 100;
			}
		}

		$filename = str_replace(' ', '_', strtolower($partner->name)) . '_' . $financial_year . '_early_warning_indicators_monthly_data';

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
