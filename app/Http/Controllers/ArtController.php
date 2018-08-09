<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class ArtController extends Controller
{

	public function current_age_breakdown()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw($this->current_art_query())
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$rows2 = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw($this->former_age_current_query())
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

		$data['outcomes'][0]['name'] = "Below 1";
		$data['outcomes'][1]['name'] = "Below 15";
		$data['outcomes'][2]['name'] = "Above 15";
		$data['outcomes'][3]['name'] = "Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "Spline";

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->below1 + $rows2[$key]->below1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below_10 + $row->below15 + $rows2[$key]->below15;
			$data["outcomes"][2]["data"][$key] = (int) $row->below_20 + $row->below25 + $row->above25 + $rows2[$key]->above15;
			$data["outcomes"][3]["data"][$key] = (int) $target->total;
		}
		return view('charts.bar_graph', $data);
	}


	public function former_age_current_query()
	{
		return "
			SUM(`currently_on_art_-_below_1_year`) AS `below1`,
			(SUM(`currently_on_art_-_male_below_15_years`) + SUM(`currently_on_art_-_female_below_15_years`)) AS `below15`,
			(SUM(`currently_on_art_-_male_above_15_years`) + SUM(`currently_on_art_-_female_above_15_years`)) AS `above15`,
		";
	}
}
