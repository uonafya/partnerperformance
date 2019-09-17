<?php

namespace App\Http\Controllers\Former;

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

	public function testing()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_prevention_of_mother-to-child_transmission')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_prevention_of_mother-to-child_transmission.facility')
			->selectRaw("(SUM(`initial_test_at_anc_hv02-04`) + SUM(`initial_test_at_l&d_hv02-05`) + 	SUM(`initial_test_at_pnc_pnc<=6wks_hv02-06`)) AS `tests`, 
				SUM(`total_positive_(add_hv02-10_-_hv02-14)_hv02-15`) AS `pos`
			 ")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$old_column = "SUM(`total_tested_(pmtct)`) AS `tests`, SUM(`total_positive_(pmtct)`) AS `pos` ";

		$rows2 = DB::table('d_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_pmtct.facility')
			->selectRaw($old_column)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positive Tests";
		$data['outcomes'][1]['name'] = "Negative Tests";
		$data['outcomes'][2]['name'] = "Positivity";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$old_table = "`d_pmtct`";
		$new_table = "`d_prevention_of_mother-to-child_transmission`";

		// $old_column = "`started_on_art_during_anc`";
		$new_column = "`initial_test_at_anc_hv02-04`";

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);

			$duplicate_pmtct = DB::select(
				DB::raw("CALL `proc_get_duplicate_total_multiple`('{$old_table}', '{$new_table}', '{$old_column}', '{$new_column}', '{$divisions_query}', {$row->year}, {$row->month});"));

			$tests = $row->tests + $rows2[$key]->tests - ($duplicate_pmtct[0]->tests ?? 0);
			$pos = $row->pos + $rows2[$key]->pos - ($duplicate_pmtct[0]->pos ?? 0);

			$data["outcomes"][0]["data"][$key] = (int) $pos;
			$data["outcomes"][1]["data"][$key] = (int) ($tests - $pos);
			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($pos, $tests);
		}
		return view('charts.dual_axis', $data);

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

		$date_query = Lookup::apidb_date_query();
		$api_rows = DB::table("apidb.site_summary")
			->join('hcm.view_facilitys', 'view_facilitys.id', '=', 'site_summary.facility')
			->selectRaw("SUM(`infantsless2m`) as `l2m`, SUM(`infantsabove2m`) as `g2m` ")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$data['div'] = str_random(15);
		
		$data['outcomes'][0]['name'] = "> 2 months (DHIS)";
		$data['outcomes'][1]['name'] = "< 2 months (DHIS)";
		$data['outcomes'][2]['name'] = "> 2 months (NASCOP)";
		$data['outcomes'][3]['name'] = "< 2 months (NASCOP)";
		// $data['outcomes'][2]['name'] = "< 2 months Contribution";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";

		$data['outcomes'][0]['stack'] = 'dhis';
		$data['outcomes'][1]['stack'] = 'dhis';
		$data['outcomes'][2]['stack'] = 'apidb';
		$data['outcomes'][3]['stack'] = 'apidb';


		// $data['outcomes'][2]['type'] = "spline";



		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->g2m + $rows2[$key]->g2m;
			$data["outcomes"][1]["data"][$key] = (int) $row->l2m + $rows2[$key]->l2m;

			$data["outcomes"][2]["data"][$key] = (int) $api_rows[$key]->g2m ?? 0;
			$data["outcomes"][3]["data"][$key] = (int) $api_rows[$key]->l2m ?? 0;

			// $data["outcomes"][2]["data"][$key] = Lookup::get_percentage($data["outcomes"][1]["data"][$key], ($data["outcomes"][1]["data"][$key] + $data["outcomes"][0]["data"][$key]));
		}
		return view('charts.bar_graph', $data);
	}


}
