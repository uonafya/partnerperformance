<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class TestingController extends Controller
{
	private $my_table = 'm_testing';

	public function testing_outcomes()
	{
		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw("SUM(testing_total) AS tests, SUM(positive_total) as pos")
			->when(true, $this->get_callback('tests'))
			->get();

		$sql2 = "
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";

		$target_obj = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilities', 'view_facilities.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->when(true, $this->target_callback())
			->whereRaw(Lookup::active_partner_query())
			->get();

		$groupby = session('filter_groupby', 1);
		$divisor = Lookup::get_target_divisor();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positive Tests";
		$data['outcomes'][1]['name'] = "Negative Tests";
		$data['outcomes'][2]['name'] = "Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";

		if($groupby > 9){
			$t = $target_obj->first()->tests;
			$target = round(($t / $divisor), 2);
		}

		Lookup::splines($data, [2]);

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->pos;
			$data["outcomes"][1]["data"][$key] = (int) ($row->tests - $row->pos);
			if(isset($target)) $data["outcomes"][2]["data"][$key] = $target;
			else{
				$t = $target_obj->where('div_id', $row->div_id)->first()->tests ?? 0;
				$data["outcomes"][2]["data"][$key] = round(($t / $divisor), 2);
			}
		}	
		return view('charts.bar_graph', $data);
	}

	public function testing_gender()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table('d_hiv_testing_and_prevention_services')
			->when(true, $this->get_joins_callback('d_hiv_testing_and_prevention_services'))
			->selectRaw("
			SUM(`tested_1-9_hv01-01`) as below_10_test,
    		(SUM(`tested_10-14_(m)_hv01-02`) + SUM(`tested_15-19_(m)_hv01-04`) + SUM(`tested_20-24(m)_hv01-06`) + SUM(`tested_25pos_(m)_hv01-08`)) AS male_test,
    		(SUM(`tested_10-14(f)_hv01-03`) + SUM(`tested_15-19(f)_hv01-05`) + SUM(`tested_20-24(f)_hv01-07`) + SUM(`tested_25pos_(f)_hv01-09`)) AS female_test,
			SUM(`positive_1-9_hv01-17`) as below_10_pos,
			(SUM(`positive_10-14(m)_hv01-18`) + SUM(`positive_15-19(m)_hv01-20`) + SUM(`positive_20-24(m)_hv01-22`) + SUM(`positive_25pos(m)_hv01-24`)) as male_pos,
			(SUM(`positive_10-14(f)_hv01-19`) + SUM(`positive_15-19(f)_hv01-21`) + SUM(`positive_20-24(f)_hv01-23`) + SUM(`positive_25pos(f)_hv01-25`)) as female_pos
			")
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
	
	public function testing_age()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table('d_hiv_testing_and_prevention_services')
			->when(true, $this->get_joins_callback('d_hiv_testing_and_prevention_services'))
			->selectRaw( "
    		SUM(`tested_1-9_hv01-01`) as below_10,
			(SUM(`tested_10-14_(m)_hv01-02`) + SUM(`tested_10-14(f)_hv01-03`)) as below_15,
			(SUM(`tested_15-19_(m)_hv01-04`) + SUM(`tested_15-19(f)_hv01-05`)) as below_20,
			(SUM(`tested_20-24(m)_hv01-06`) + SUM(`tested_20-24(f)_hv01-07`)) as below_25,
			(SUM(`tested_25pos_(m)_hv01-08`) + SUM(`tested_25pos_(f)_hv01-09`)) as above_25,

			SUM(`positive_1-9_hv01-17`) as below_10_pos,
			(SUM(`positive_10-14(m)_hv01-18`) + SUM(`positive_10-14(f)_hv01-19`)) as below_15_pos,
			(SUM(`positive_15-19(m)_hv01-20`) + SUM(`positive_15-19(f)_hv01-21`)) as below_20_pos,
			(SUM(`positive_20-24(m)_hv01-22`) + SUM(`positive_20-24(f)_hv01-23`)) as below_25_pos,
			(SUM(`positive_25pos(m)_hv01-24`) + SUM(`positive_25pos(f)_hv01-25`)) as above_25_pos
	    	")
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

	public function positivity()
	{
		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw("SUM(testing_total) AS tests, SUM(positive_total) as pos")
			->when(true, $this->get_callback('tests'))
			->get();

		$sql2 = "
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";

		$target_obj = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilities', 'view_facilities.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->when(true, $this->target_callback())
			->whereRaw(Lookup::active_partner_query())
			->get();

		$groupby = session('filter_groupby', 1);
		$divisor = Lookup::get_target_divisor();

		if($groupby > 9){
			$t = $target_obj->first()->tests;
			$p = $target_obj->first()->pos;
			$target_tests = round(($t / $divisor), 2);
			$target_pos = round(($p / $divisor), 2);

			$target = Lookup::get_percentage($p, $t);
		}

		$data['div'] = str_random(15);
		$data['yAxis'] = 'Percentage';

		$data['outcomes'][0]['name'] = "Positivity";
		$data['outcomes'][1]['name'] = "Targeted Positivity";

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = Lookup::get_percentage($row->pos, $row->tests);
			if(isset($target)) $data["outcomes"][1]["data"][$key] = $target;
			else{
				$obj = $target_obj->where('div_id', $row->div_id)->first();
				if($obj){
					$target_tests = round(($obj->tests / $divisor), 2);
					$target_pos = round(($obj->pos / $divisor), 2);
					$data["outcomes"][1]["data"][$key] = Lookup::get_percentage($target_pos, $target_tests);
				}else{
					$data["outcomes"][1]["data"][$key] = 0;					
				}
			}
		}	
		return view('charts.line_graph', $data);
	}

	public function pos_gender()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw("SUM(positive_below10) as below_10,
					(SUM(positive_below15_m) + SUM(positive_below20_m) + SUM(positive_below25_m) + SUM(positive_above25_m)) AS male_pos,
					(SUM(positive_below15_f) + SUM(positive_below20_f) + SUM(positive_below25_f) + SUM(positive_above25_f)) AS female_pos
				")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['paragraph'] = "
		<table class='table table-striped'>
			<tr> <td>Below 10 (Not disaggregated) : </td> <td>" . number_format($row->below_10) . "</td> </tr>
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

	public function pos_age()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw("SUM(positive_below10) as below_10,
				(SUM(positive_below15_m) + SUM(positive_below15_f)) as below_15,
				(SUM(positive_below20_m) + SUM(positive_below20_f)) as below_20,
				(SUM(positive_below25_m) + SUM(positive_below25_f)) as below_25,
				(SUM(positive_above25_m) + SUM(positive_above25_f)) as above_25
			 ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['paragraph'] = "
		<table class='table table-striped'>
			<tr> <td>&lt; 10 : </td> <td>" . number_format($row->below_10) . "</td> </tr>
			<tr> <td>&lt; 15 : </td> <td>" . number_format($row->below_15) . "</td> </tr>
			<tr> <td>&lt; 20 : </td> <td>" . number_format($row->below_20) . "</td> </tr>
			<tr> <td>&lt; 25 : </td> <td>" . number_format($row->below_25) . "</td> </tr>
			<tr> <td>&gt; 25 : </td> <td>" . number_format($row->above_25) . "</td> </tr>
			<tr>
				<td>Total : </td> <td>" . number_format($row->below_10 + $row->below_15 + $row->below_20 + $row->below_25 + $row->above_25) . "</td>
			</tr>
		</table>			
		";

		$data['div'] = str_random(15);

		$data['outcomes']['name'] = "Tests";
		$data['outcomes']['colorByPoint'] = true;


		$data['outcomes']['data'][0]['name'] = "&lt; 10";
		$data['outcomes']['data'][1]['name'] = "&lt; 15";
		$data['outcomes']['data'][2]['name'] = "&lt; 20";
		$data['outcomes']['data'][3]['name'] = "&lt; 25";
		$data['outcomes']['data'][4]['name'] = "&gt; 25";

		$data['outcomes']['data'][0]['y'] = (int) ($row->below_10);
		$data['outcomes']['data'][1]['y'] = (int) ($row->below_15);
		$data['outcomes']['data'][2]['y'] = (int) $row->below_20;
		$data['outcomes']['data'][3]['y'] = (int) $row->below_25;
		$data['outcomes']['data'][4]['y'] = (int) $row->above_25;

		return view('charts.pie_chart', $data);
	}
	
	public function discordancy()
	{
    	$groupby = session('filter_groupby', 1);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw("SUM(tested_couples) AS tests, SUM(discordant_couples) as pos")
			->when(true, $this->get_callback('tests'))
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Discordant Couples";
		$data['outcomes'][1]['name'] = "Cocordant Couples";
		$data['outcomes'][2]['name'] = "Discordancy";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		Lookup::splines($data, [2]);

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->pos;
			$data["outcomes"][1]["data"][$key] = (int) $row->tests - $row->pos;

			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($row->pos, $row->tests);

		}
		return view('charts.dual_axis', $data);
	}

	public function testing_summary()
	{
		$data = Lookup::table_data();

		$sql = "
			SUM(`tested_1-9_hv01-01`) as below_10,
			SUM(`tested_10-14_(m)_hv01-02`) as below_15_m, SUM(`tested_10-14(f)_hv01-03`) as below_15_f,
			SUM(`tested_15-19_(m)_hv01-04`) as below_20_m, SUM(`tested_15-19(f)_hv01-05`) as below_20_f,
			SUM(`tested_20-24(m)_hv01-06`) as below_25_m, SUM(`tested_20-24(f)_hv01-07`) as below_25_f,
			SUM(`tested_25pos_(m)_hv01-08`) as above_25_m, SUM(`tested_25pos_(f)_hv01-09`) as above_25_f,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) as total";

		$data['rows'] = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilities', 'view_facilities.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->join('periods', 'periods.id', '=', 'd_hiv_testing_and_prevention_services.period_id')
			->selectRaw($sql)
			->when(true, $this->get_callback('total'))
			->whereRaw(Lookup::active_partner_query())
			->get();

		return view('tables.testing_summary', $data);
	}

	public function summary()
	{
		$data = Lookup::table_data();

		$data['rows'] = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw("SUM(testing_total) AS tests, SUM(positive_total) as pos")
			->when(true, $this->get_callback('tests'))
			->get();

		$data['linked'] = DB::table('m_art')
			->when(true, $this->get_joins_callback('m_art'))
			->selectRaw("SUM(new_total) AS newtx")
			->when(true, $this->get_callback('newtx'))
			->get();

		$sql2 = "
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos,
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests
		";

		$data['targets'] = DB::table('t_hiv_testing_and_prevention_services')
			->join('view_facilities', 'view_facilities.id', '=', 't_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql2)
			->when(true, $this->target_callback())
			->whereRaw(Lookup::active_partner_query())
			->get();

		// dd($data);

		return view('tables.summary', $data);
	}


}
