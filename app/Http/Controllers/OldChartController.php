<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class OldChartController extends Controller
{


	public function treatment()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$data['div'] = str_random(15);

		$actual = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("SUM(`total_currently_on_art`) AS `current`, 
							SUM(`total_starting_on_art`) AS `new_art`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$current_patients = "
			SELECT SUM(cu.current_patients) AS totals
			FROM (
				SELECT MAX(`total_currently_on_art`) as current_patients
				FROM `d_care_and_treatment`
				JOIN `view_facilitys` ON `view_facilitys`.`id`=`d_care_and_treatment`.`facility`
				WHERE {$divisions_query} AND {$date_query}
				GROUP BY `facility`
			) cu
		";

		$cu = DB::select($current_patients);

		$date_query = Lookup::date_query(true);
		$target = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `current`, 
							SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) AS `new_art`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['actual'] = $actual;
		$data['target'] = $target;

		$data['actual_current'] = $cu[0]->totals;

		$data['current_completion'] = Lookup::get_percentage($cu[0]->totals, $target->current);
		$data['new_completion'] = Lookup::get_percentage($actual->new_art, $target->new_art);

		$data['current_status'] = Lookup::progress_status($data['current_completion']);
		$data['new_status'] = Lookup::progress_status($data['new_completion']);

		return view('tables.treatment', $data);
	}

	public function current()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("SUM(`total_currently_on_art`) AS `total`")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$date_query = Lookup::date_query(true);
		$target = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `total`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['div'] = str_random(15);

		$t = round(($target->total), 2);

		$data['outcomes'][0]['name'] = "Totals";
		$data['outcomes'][1]['name'] = "Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "spline";

		// $data['outcomes'][0]['yAxis'] = 1;
		// $data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		// $data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');

		foreach ($rows as $key => $row) {
			$m = Lookup::resolve_month($row->month);
			$data['categories'][$key] = substr($m, 0, 3) . ', ' . $row->year;
			$data["outcomes"][0]["data"][$key] = (int) $row->total;
			$data["outcomes"][1]["data"][$key] = $t;
		}

		return view('charts.bar_graph', $data);
	}

	public function art_new()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("SUM(`total_starting_on_art`) AS `total`")
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$date_query = Lookup::date_query(true);
		$target = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) AS `total`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['div'] = str_random(15);

		$t = round(($target->total / 12), 2);

		$data['outcomes'][0]['name'] = "Totals";
		$data['outcomes'][1]['name'] = "Monthly Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "spline";

		// $data['outcomes'][0]['yAxis'] = 1;
		// $data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		// $data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		foreach ($rows as $key => $row) {
			$m = Lookup::resolve_month($row->month);
			$data['categories'][$key] = substr($m, 0, 3) . ', ' . $row->year;
			$data["outcomes"][0]["data"][$key] = (int) $row->total;
			$data["outcomes"][1]["data"][$key] = $t;
		}

		return view('charts.bar_graph', $data);
	}

	public function testing_gender()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($this->old_gender_pos_query())
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['paragraph'] = "
		<table class='table table-striped'>
			<tr> <td>Male : </td> <td>" . number_format($row->male_pos) . "</td> </tr>
			<tr> <td>Female : </td> <td>" . number_format($row->female_pos) . "</td> </tr>
			<tr>
				<td>Total : </td> <td>" . number_format($row->male_pos + $row->female_pos) . "</td>
			</tr>
		</table>			
		";

		$data['div'] = str_random(15);

		$data['outcomes']['name'] = "Tests";
		$data['outcomes']['colorByPoint'] = true;


		$data['outcomes']['data'][0]['name'] = "Male";
		$data['outcomes']['data'][1]['name'] = "Female";

		$data['outcomes']['data'][0]['y'] = (int) $row->male_pos;
		$data['outcomes']['data'][1]['y'] = (int) $row->female_pos;

		return view('charts.pie_chart', $data);
	}

	public function outcome_gender()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($this->old_gender_pos_query())
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Tests";

		$data['outcomes'][0]['type'] = "column";

		// $data['outcomes'][0]['yAxis'] = 1;
		// $data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		// $data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		$data['categories'][0] = 'male';
		$data['categories'][1] = 'female';

		$data["outcomes"][0]["data"][0] = (int) $row->male_pos;
		$data["outcomes"][0]["data"][1] = (int) $row->female_pos;

		return view('charts.bar_graph', $data);
	}

	public function testing_age()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($this->old_age_query())
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['paragraph'] = "
		<table class='table table-striped'>
			<tr> <td>&lt; 14 : </td> <td>" . number_format($row->below_15) . "</td> </tr>
			<tr> <td>&gt; 14 & &lt; 25: </td> <td>" . number_format($row->below_25) . "</td> </tr>
			<tr> <td>&gt; 25: </td> <td>" . number_format($row->above_25) . "</td> </tr>
			<tr>
				<td>Total : </td> <td>" . number_format($row->below_15 + $row->below_25 + $row->above_25) . "</td>
			</tr>
		</table>			
		";

		$data['div'] = str_random(15);

		$data['outcomes']['name'] = "Tests";
		$data['outcomes']['colorByPoint'] = true;


		$data['outcomes']['data'][0]['name'] = "&lt; 15";
		$data['outcomes']['data'][1]['name'] = "&gt; 15 & &lt; 25";
		$data['outcomes']['data'][2]['name'] = "&gt; 25";

		$data['outcomes']['data'][0]['y'] = (int) ($row->below_15);
		$data['outcomes']['data'][1]['y'] = (int) ($row->below_25);
		$data['outcomes']['data'][2]['y'] = (int) $row->above_25;

		return view('charts.pie_chart', $data);
	}

	public function outcome_age()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($this->old_age_query())
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Tests";

		$data['outcomes'][0]['type'] = "column";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');

		$data['categories'][0] = '&lt; 15';
		$data['categories'][1] = '&gt; 15 & &lt; 25';
		$data['categories'][2] = '&gt; 25';

		$data["outcomes"][0]["data"][0] = (int) ($row->below_15);
		$data["outcomes"][0]["data"][1] = (int) ($row->below_25);
		$data["outcomes"][0]["data"][2] = (int) $row->above_25;
		return view('charts.bar_graph', $data);
	}

	public function pmtct()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$sql = "
    		SUM(`total_tested_(pmtct)`) AS total_pmtct,
    		SUM(`started_on_art_during_anc`) AS art_anc
		";

		$rows = DB::table('d_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_pmtct.facility')
			->selectRaw($sql)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Total PMTCT";
		$data['outcomes'][1]['name'] = "Started on ART during ANC";

		foreach ($rows as $key => $row) {
			$m = Lookup::resolve_month($row->month);
			$data['categories'][$key] = substr($m, 0, 3) . ', ' . $row->year;
			$data["outcomes"][0]["data"][$key] = (int) $row->total_pmtct;
			$data["outcomes"][1]["data"][$key] = (int) $row->art_anc;
		}

		return view('charts.line_graph', $data);
	}

	public function eid()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$sql = "
    		SUM(`pcr_(within_2_months)_infant_testing_(initial_test_only)`) AS below_2m,
    		SUM(`pcr_(from3_to_8_months)_infant_testing_(initial_test_only)`) AS below_9m,
    		SUM(`pcr_(from_9_to_12_months)_infant_testing_(initial_test_only)`) AS below_12m,

    		SUM(`pcr_(by_2_months)_confirmed_infant_test_results_positive`) AS below_2m_pos,
    		SUM(`pcr_(3_to_8_months)_confirmed_infant_test_results_positive`) AS below_9m_pos,
    		SUM(`pcr_(9_to_12_months)_confirmed_infant_test_results_positive`) AS below_12m_pos
		";

		$rows = DB::table('d_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_pmtct.facility')
			->selectRaw($sql)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Initial PCR &lt;8 weeks";
		$data['outcomes'][1]['name'] = "Initial PCR &lt;8 weeks pos";
		$data['outcomes'][2]['name'] = "Initial PCR 2-8 months";
		$data['outcomes'][3]['name'] = "Initial PCR 2-8 months pos";
		$data['outcomes'][4]['name'] = "Initial PCR 9-12 months";
		$data['outcomes'][5]['name'] = "Initial PCR 9-12 months pos";

		foreach ($rows as $key => $row) {
			$m = Lookup::resolve_month($row->month);
			$data['categories'][$key] = substr($m, 0, 3) . ', ' . $row->year;
			$data["outcomes"][0]["data"][$key] = (int) $row->below_2m;
			$data["outcomes"][1]["data"][$key] = (int) $row->below_2m_pos;
			$data["outcomes"][2]["data"][$key] = (int) $row->below_9m;
			$data["outcomes"][3]["data"][$key] = (int) $row->below_9m_pos;
			$data["outcomes"][4]["data"][$key] = (int) $row->below_12m;
			$data["outcomes"][5]["data"][$key] = (int) $row->below_12m_pos;
		}

		return view('charts.line_graph', $data);
	}

}
