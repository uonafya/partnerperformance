<?php

namespace App\Http\Controllers\Former;

use Illuminate\Http\Request;
use DB;
use App\Lookup;


class ChartController extends Controller
{

	public function treatment()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$data['div'] = str_random(15);

		$actual = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `current`, 
							SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) AS `new_art`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$current_patients = "
			SELECT SUM(cu.current_patients) AS totals
			FROM (
				SELECT MAX(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) as current_patients
				FROM `d_hiv_and_tb_treatment`
				JOIN `view_facilitys` ON `view_facilitys`.`id`=`d_hiv_and_tb_treatment`.`facility`
				WHERE {$divisions_query} AND {$date_query}
				GROUP BY `facility`
			) cu
		";

		$cu = DB::select($current_patients);

		// return [$cu, $actual];

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

		$rows = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `total`")
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

		$rows = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) AS `total`")
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

		$row = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($this->gender_query())
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['paragraph'] = "
		<table class='table table-striped'>
			<tr> <td>Below 10 : </td> <td>" . number_format($row->below_10_test) . "</td> </tr>
			<tr> <td>Male : </td> <td>" . number_format($row->male_test) . "</td> </tr>
			<tr> <td>Female : </td> <td>" . number_format($row->female_test) . "</td> </tr>
			<tr>
				<td>Total : </td> <td>" . number_format($row->below_10_test + $row->male_test + $row->female_test) . "</td>
			</tr>
		</table>			
		";

		$data['div'] = str_random(15);

		$data['outcomes']['name'] = "Tests";
		$data['outcomes']['colorByPoint'] = true;


		$data['outcomes']['data'][0]['name'] = "Male";
		$data['outcomes']['data'][1]['name'] = "Female";

		$data['outcomes']['data'][0]['y'] = (int) $row->male_test;
		$data['outcomes']['data'][1]['y'] = (int) $row->female_test;

		return view('charts.pie_chart', $data);
	}

	public function outcome_gender()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($this->gender_query())
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positives";
		$data['outcomes'][1]['name'] = "Negatives";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";

		// $data['outcomes'][0]['yAxis'] = 1;
		// $data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		// $data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		$data['categories'][0] = 'male';
		$data['categories'][1] = 'female';

		$data["outcomes"][0]["data"][0] = (int) $row->male_pos;
		$data["outcomes"][1]["data"][0] = (int) ($row->male_test - $row->male_pos);

		$data["outcomes"][0]["data"][1] = (int) $row->female_pos;
		$data["outcomes"][1]["data"][1] = (int) ($row->female_test - $row->female_pos);

		return view('charts.bar_graph', $data);
	}

	public function testing_age()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($this->age_query())
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['paragraph'] = "
		<table class='table table-striped'>
			<tr> <td>&lt; 15 : </td> <td>" . number_format($row->below_10 + $row->below_15) . "</td> </tr>
			<tr> <td>&gt; 15 & &lt; 25: </td> <td>" . number_format($row->below_20 + $row->below_25) . "</td> </tr>
			<tr> <td>&gt; 25: </td> <td>" . number_format($row->above_25) . "</td> </tr>
			<tr>
				<td>Total : </td> <td>" . number_format($row->below_10 + $row->below_15 + $row->below_20 + $row->below_25 + $row->above_25) . "</td>
			</tr>
		</table>			
		";

		$data['div'] = str_random(15);

		$data['outcomes']['name'] = "Tests";
		$data['outcomes']['colorByPoint'] = true;


		$data['outcomes']['data'][0]['name'] = "&lt; 15";
		$data['outcomes']['data'][1]['name'] = "&gt; 15 & &lt; 25";
		$data['outcomes']['data'][2]['name'] = "&gt; 25";

		$data['outcomes']['data'][0]['y'] = (int) ($row->below_10 + $row->below_15);
		$data['outcomes']['data'][1]['y'] = (int) ($row->below_20 + $row->below_25);
		$data['outcomes']['data'][2]['y'] = (int) $row->above_25;

		return view('charts.pie_chart', $data);
	}

	public function outcome_age()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($this->age_query())
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positives";
		$data['outcomes'][1]['name'] = "Negatives";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";

		// $data['outcomes'][0]['yAxis'] = 1;
		// $data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');

		$data['categories'][0] = '&lt; 15';
		$data['categories'][1] = '&gt; 15 & &lt; 25';
		$data['categories'][2] = '&gt; 25';

		$data["outcomes"][0]["data"][0] = (int) ($row->below_10_pos + $row->below_15_pos);
		$data["outcomes"][1]["data"][0] = (int) (($row->below_10 + $row->below_15) - ($row->below_10_pos + $row->below_15_pos));

		$data["outcomes"][0]["data"][1] = (int) ($row->below_20_pos + $row->below_25_pos);
		$data["outcomes"][1]["data"][1] = (int) (($row->below_20 + $row->below_25) - ($row->below_20_pos + $row->below_25_pos));

		$data["outcomes"][0]["data"][2] = (int) $row->above_25_pos;
		$data["outcomes"][1]["data"][2] = (int) ($row->above_25 - $row->above_25_pos);


		return view('charts.bar_graph', $data);
	}

	public function pmtct()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_prevention_of_mother-to-child_transmission')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_prevention_of_mother-to-child_transmission.facility')
			->selectRaw($this->pmtct_query())
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "New PMTCT";
		$data['outcomes'][1]['name'] = "Positive PMTCT";

		foreach ($rows as $key => $row) {
			$m = Lookup::resolve_month($row->month);
			$data['categories'][$key] = substr($m, 0, 3) . ', ' . $row->year;
			$data["outcomes"][0]["data"][$key] = (int) $row->new_pmtct;
			$data["outcomes"][1]["data"][$key] = (int) $row->positive_pmtct;
		}

		return view('charts.line_graph', $data);
	}

	public function eid()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_prevention_of_mother-to-child_transmission')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_prevention_of_mother-to-child_transmission.facility')
			->selectRaw($this->eid_query())
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Initial PCR &lt;8 weeks";
		$data['outcomes'][1]['name'] = "Initial PCR 2-12 months";

		foreach ($rows as $key => $row) {
			$m = Lookup::resolve_month($row->month);
			$data['categories'][$key] = substr($m, 0, 3) . ', ' . $row->year;
			$data["outcomes"][0]["data"][$key] = (int) $row->below_2m;
			$data["outcomes"][1]["data"][$key] = (int) $row->below_12m;
		}

		return view('charts.line_graph', $data);
	}
}
