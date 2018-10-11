<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class OldChartController extends Controller
{

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
}
