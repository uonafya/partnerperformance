<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class ChartController extends Controller
{

	public function testing_gender()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$row = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
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
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
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
}
