<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class ChartController extends Controller
{

	public function summary()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$sql = $q['select_query'] .  ",
			SUM(`tested_1-9_hv01-01` + `tested_10-14_(m)_hv01-02` + `tested_10-14(f)_hv01-03` + `tested_15-19_(m)_hv01-04` + `tested_15-19(f)_hv01-05` + `tested_20-24(m)_hv01-06` + `tested_20-24(f)_hv01-07` + `tested_25pos_(m)_hv01-08` + `tested_25pos_(f)_hv01-09`) AS `tested_total`,
			SUM(`positive_1-9_hv01-17` + `positive_10-14(m)_hv01-18` + `positive_10-14(f)_hv01-19` + `positive_15-19(m)_hv01-20` + `positive_15-19(f)_hv01-21` + `positive_20-24(m)_hv01-22` + `positive_20-24(f)_hv01-23` + `positive_25pos(m)_hv01-24` + `positive_25pos(f)_hv01-25`) AS `positive_total`,
			SUM(`linked_1-9_yrs_hv01-30` + `linked_10-14_hv01-31` + `linked_15-19_hv01-32` + `linked_20-24_hv01-33` + `linked_25pos_hv01-34`) AS `linked_total`
		";

		$data['div'] = str_random(15);

		// DB::enableQueryLog();

		$data['rows'] = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		// return DB::getQueryLog();

		return view('tables.testing_summary', $data);
	}

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

		$t = round(($target->total / 12), 2);

		$data['outcomes'][0]['name'] = "Totals";
		$data['outcomes'][1]['name'] = "Monthly Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "spline";

		$data['outcomes'][0]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		// $data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		foreach ($rows as $key => $row) {
			$m = Lookup::resolve_month($row->month);
			$data['categories'][$key] = substr($m, 0, 3) . ', ' . $row->year;
			$data["outcomes"][0]["data"][$key] = (int) $row->total;
			$data["outcomes"][1]["data"][$key] = $t;
		}

		return view('charts.dual_axis', $data);
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

		$data['outcomes'][0]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		// $data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		foreach ($rows as $key => $row) {
			$m = Lookup::resolve_month($row->month);
			$data['categories'][$key] = substr($m, 0, 3) . ', ' . $row->year;
			$data["outcomes"][0]["data"][$key] = (int) $row->total;
			$data["outcomes"][1]["data"][$key] = $t;
		}

		return view('charts.dual_axis', $data);
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

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		// $data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		$data['categories'][0] = 'male';
		$data['categories'][1] = 'female';

		$data["outcomes"][0]["data"][0] = (int) $row->male_pos;
		$data["outcomes"][1]["data"][0] = (int) ($row->male_test - $row->male_pos);

		$data["outcomes"][0]["data"][1] = (int) $row->female_pos;
		$data["outcomes"][1]["data"][1] = (int) ($row->female_test - $row->female_pos);

		return view('charts.dual_axis', $data);
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
			<tr> <td>&lt; 14 : </td> <td>" . number_format($row->below_10 + $row->below_15) . "</td> </tr>
			<tr> <td>&gt; 14 & &lt; 25: </td> <td>" . number_format($row->below_20 + $row->below_25) . "</td> </tr>
			<tr> <td>&gt; 25: </td> <td>" . number_format($row->above_25) . "</td> </tr>
			<tr>
				<td>Total : </td> <td>" . number_format($row->below_10 + $row->below_15 + $row->below_20 + $row->below_25 + $row->above_25) . "</td>
			</tr>
		</table>			
		";

		$data['div'] = str_random(15);

		$data['outcomes']['name'] = "Tests";
		$data['outcomes']['colorByPoint'] = true;


		$data['outcomes']['data'][0]['name'] = "&lt; 14";
		$data['outcomes']['data'][1]['name'] = "&gt; 14 & &lt; 25";
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

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');

		$data['categories'][0] = '&lt; 14';
		$data['categories'][1] = '&gt; 14 & &lt; 25';
		$data['categories'][2] = '&gt; 25';

		$data["outcomes"][0]["data"][0] = (int) ($row->below_10_pos + $row->below_15_pos);
		$data["outcomes"][1]["data"][0] = (int) (($row->below_10 + $row->below_15) - ($row->below_10_pos + $row->below_15_pos));

		$data["outcomes"][0]["data"][1] = (int) ($row->below_20_pos + $row->below_25_pos);
		$data["outcomes"][1]["data"][1] = (int) (($row->below_20 + $row->below_25) - ($row->below_20_pos + $row->below_25_pos));

		$data["outcomes"][0]["data"][2] = (int) $row->above_25_pos;
		$data["outcomes"][1]["data"][2] = (int) ($row->above_25 - $row->above_25_pos);


		return view('charts.dual_axis', $data);
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

    public function gender_query()
    {
    	return "
			SUM(`tested_1-9_hv01-01`) as below_10_test,
    		SUM(`tested_10-14_(m)_hv01-02` + `tested_15-19_(m)_hv01-04` + `tested_20-24(m)_hv01-06` + `tested_25pos_(m)_hv01-08`) AS male_test,
    		SUM(`tested_10-14(f)_hv01-03` + `tested_15-19(f)_hv01-05` + `tested_20-24(f)_hv01-07` + `tested_25pos_(f)_hv01-09`) AS female_test,
			SUM(`positive_1-9_hv01-17`) as below_10_pos,
			SUM(`positive_10-14(m)_hv01-18` + `positive_15-19(m)_hv01-20` + `positive_20-24(m)_hv01-22` + `positive_25pos(m)_hv01-24`) as male_pos,
			SUM(`positive_10-14(f)_hv01-19` + `positive_15-19(f)_hv01-21` + `positive_20-24(f)_hv01-23` + `positive_25pos(f)_hv01-25`) as female_pos
		";
    }

    public function age_query()
    {
    	return "
    		SUM(`tested_1-9_hv01-01`) as below_10,
			SUM(`tested_10-14_(m)_hv01-02` + `tested_10-14(f)_hv01-03`) as below_15,
			SUM(`tested_15-19_(m)_hv01-04` + `tested_15-19(f)_hv01-05`) as below_20,
			SUM(`tested_20-24(m)_hv01-06` + `tested_20-24(f)_hv01-07`) as below_25,
			SUM(`tested_25pos_(m)_hv01-08` + `tested_25pos_(f)_hv01-09`) as above_25,

			SUM(`positive_1-9_hv01-17`) as below_10_pos,
			SUM(`positive_10-14(m)_hv01-18` + `positive_10-14(f)_hv01-19`) as below_15_pos,
			SUM(`positive_15-19(m)_hv01-20` + `positive_15-19(f)_hv01-21`) as below_20_pos,
			SUM(`positive_20-24(m)_hv01-22` + `positive_20-24(f)_hv01-23`) as below_25_pos,
			SUM(`positive_25pos(m)_hv01-24` + `positive_25pos(f)_hv01-25`) as above_25_pos
    	";
    }

    public function eid_query()
    {
    	return "
    		SUM(`initial_pcr_<_8wks_hv02-44`) as below_2m,
    		SUM(`initial_pcr_>8wks_-12_mths_hv02-45`) as below_12m
    	";

    }
}
