<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class ChartController extends Controller
{
	public function treatment()
	{

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

		$target = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `total`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['div'] = str_random(15);


		$data['outcomes'][0]['name'] = "Totals";
		$data['outcomes'][1]['name'] = "Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "spline";

		$data['outcomes'][0]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		// $data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		foreach ($rows as $key => $row) {
			$m = Lookup::resolve_month($row->month);
			$data['categories'][$key] = substr($m, 0, 3) . ', ' $row->year;
			$data["outcomes"][0]["data"][$key]	= (int) $row->total;
			$data["outcomes"][1]["data"][$key]	= (int) $target->total / 12;
		}

		return view('charts.dual_axis', $data);
	}
}
