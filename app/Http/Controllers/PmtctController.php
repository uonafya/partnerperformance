<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class PmtctController extends Controller
{

	public function haart()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_prevention_of_mother-to-child_transmission')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_prevention_of_mother-to-child_transmission.facility')
			->selectRaw("SUM(`on_maternal_haart_total_hv02-20`) AS `total`")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$rows2 = DB::table('d_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_pmtct.facility')
			->selectRaw("SUM(`haart_(art)`) AS `total`")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$old_table = "`d_pmtct`";
		$new_table = "`d_prevention_of_mother-to-child_transmission`";

		$old_column = "`haart_(art)`";
		$new_column = "`on_maternal_haart_total_hv02-20`";

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Patients";
		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);

			$duplicate = DB::select(
				DB::raw("CALL `proc_get_duplicate_total`('{$old_table}', '{$new_table}', '{$old_column}', '{$new_column}', '{$divisions_query}', {$row->year}, {$row->month});"));

			$data["outcomes"][0]["data"][$key] = (int) $row->total + $rows2[$key]->total - ($duplicate[0]->total ?? 0);
		}
		return view('charts.bar_graph', $data);
	}

	public function starting_point()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_prevention_of_mother-to-child_transmission')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_prevention_of_mother-to-child_transmission.facility')
			->selectRaw("SUM(`start_haart_anc_hv02-17`) AS `anc`, SUM(`start_haart_l&d_hv02-18`) AS `lnd`, SUM(`start_haart_pnc<=6wks_hv02-19`) AS `pnc6w`, SUM(`start_haart_pnc>_6weeks_to_6_months_hv02-21`) AS `pnc_later`")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$rows2 = DB::table('d_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_pmtct.facility')
			->selectRaw("SUM(`started_on_art_during_anc`) AS `anc` ")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Started at PNC 6w-6m (*)";
		$data['outcomes'][1]['name'] = "Started at PNC < 6w (*)";
		$data['outcomes'][2]['name'] = "Started at L&D (*)";
		$data['outcomes'][3]['name'] = "Started at ANC";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][3]['tooltip'] = array("valueSuffix" => ' ');

		$old_table = "`d_pmtct`";
		$new_table = "`d_prevention_of_mother-to-child_transmission`";

		$old_column = "`started_on_art_during_anc`";
		$new_column = "`start_haart_anc_hv02-17`";

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);

			$duplicate_anc = DB::select(
				DB::raw("CALL `proc_get_duplicate_total`('{$old_table}', '{$new_table}', '{$old_column}', '{$new_column}', '{$divisions_query}', {$row->year}, {$row->month});"));

			$data["outcomes"][0]["data"][$key] = (int) $row->pnc_later;
			$data["outcomes"][1]["data"][$key] = (int) $row->pnc6w;
			$data["outcomes"][2]["data"][$key] = (int) $row->lnd;
			$data["outcomes"][3]["data"][$key] = (int) $row->anc + $rows2[$key]->anc - ($duplicate_anc[0]->total ?? 0);
		}
		return view('charts.bar_graph', $data);
	}

	public function discovery_positivity()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_prevention_of_mother-to-child_transmission')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_prevention_of_mother-to-child_transmission.facility')
			->selectRaw("SUM(`positive_results_anc_hv02-11`) AS `anc`, SUM(`positive_results_l&d_hv02-12`) AS `lnd`, SUM(`positive_results_pnc<=6wks_hv02-13`) AS `pnc6w`, SUM(`positive_pnc>_6weeks_to_6_months_hv02-14`) AS `pnc_later`")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$rows2 = DB::table('d_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_pmtct.facility')
			->selectRaw("SUM(`antenatal_positive_to_hiv_test`) AS `anc`, SUM(`labour_and_delivery_postive_to_hiv_test`) as `lnd` ")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positive Result at PNC 6w-6m (*)";
		$data['outcomes'][1]['name'] = "Positive Result at PNC < 6w (*)";
		$data['outcomes'][2]['name'] = "Positive Result at L&D";
		$data['outcomes'][3]['name'] = "Positive Result at ANC";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][3]['tooltip'] = array("valueSuffix" => ' ');

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->pnc_later;
			$data["outcomes"][1]["data"][$key] = (int) $row->pnc6w;
			$data["outcomes"][2]["data"][$key] = (int) $row->lnd + $rows2[$key]->lnd;
			$data["outcomes"][3]["data"][$key] = (int) $row->anc + $rows2[$key]->anc;
		}
		return view('charts.bar_graph', $data);
	}

	public function male_testing()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_prevention_of_mother-to-child_transmission')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_prevention_of_mother-to-child_transmission.facility')
			->selectRaw("(SUM(`initial_test_at_anc_male_hv02-30`)+SUM(`initial_test_at_l&d_male_hv02-31`)) AS `anc`, SUM(`initial_test_at_pnc_male_hv02-32`) AS `pnc`")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$rows2 = DB::table('d_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_pmtct.facility')
			->selectRaw("SUM(`male_partners_tested_-(_anc/l&d)`) AS `anc`")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Males Tested PNC (*)";
		$data['outcomes'][1]['name'] = "Males Tested (ANC/L&D)";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->pnc;
			$data["outcomes"][1]["data"][$key] = (int) $row->anc + $rows2[$key]->anc;
		}
		return view('charts.bar_graph', $data);
	}

	public function eid()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_prevention_of_mother-to-child_transmission')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_prevention_of_mother-to-child_transmission.facility')
			->selectRaw("SUM(`initial_pcr_<_8wks_hv02-44`) AS `l2m`, SUM(`initial_pcr_>8wks_-12_mths_hv02-45`) AS `g2m`")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$rows2 = DB::table('d_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_pmtct.facility')
			->selectRaw("SUM(`pcr_(within_2_months)_infant_testing_(initial_test_only)`) AS `l2m`, (SUM(`pcr_(from3_to_8_months)_infant_testing_(initial_test_only)`)+SUM(`pcr_(from_9_to_12_months)_infant_testing_(initial_test_only)`)) AS `g2m`")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$data['div'] = str_random(15);
		
		$data['outcomes'][0]['name'] = "< 2 months";
		$data['outcomes'][1]['name'] = "> 2 months";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->l2m + $rows2[$key]->l2m;
			$data["outcomes"][1]["data"][$key] = (int) $row->g2m + $rows2[$key]->g2m;
		}
		return view('charts.bar_graph', $data);
	}


}
