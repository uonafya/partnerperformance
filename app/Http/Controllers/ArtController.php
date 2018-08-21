<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class ArtController extends Controller
{

	public function reporting()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$dates = DB::table('d_hiv_and_tb_treatment')
			->select('year', 'month')
			->whereRaw($date_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$start_art_new_q = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->addSelect('year', 'month')
			->whereRaw("`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026` > 0")
			->whereRaw($divisions_query);

		$start_art_new = $start_art_new_q->whereRaw($date_query)->get();

		$start_art_old = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->addSelect('year', 'month')
			->whereRaw("`total_starting_on_art` > 0")
			->whereRaw($divisions_query)
			->whereRaw($date_query)
			->get();

		$current_art_new = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->addSelect('year', 'month')
			->whereRaw("`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0")
			->whereRaw($divisions_query)
			->whereRaw($date_query)
			->get();

		$current_art_old = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->addSelect('year', 'month')
			->whereRaw("`total_currently_on_art` > 0")
			->whereRaw($divisions_query)
			->whereRaw($date_query)
			->get();


		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "New tx old form";
		$data['outcomes'][1]['name'] = "New tx new form";
		$data['outcomes'][2]['name'] = "Current tx old form";
		$data['outcomes'][3]['name'] = "Current tx new form";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][3]['tooltip'] = array("valueSuffix" => ' ');

		$data['outcomes'][0]['type'] = "spline";
		$data['outcomes'][1]['type'] = "spline";
		$data['outcomes'][2]['type'] = "spline";
		$data['outcomes'][3]['type'] = "spline";

		foreach ($dates as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = $this->check_null($start_art_old->where('year', $row->year)->where('month', $row->month)->first());
			$data["outcomes"][1]["data"][$key] = $this->check_null($start_art_new->where('year', $row->year)->where('month', $row->month)->first());
			$data["outcomes"][2]["data"][$key] = $this->check_null($current_art_old->where('year', $row->year)->where('month', $row->month)->first());
			$data["outcomes"][3]["data"][$key] = $this->check_null($current_art_new->where('year', $row->year)->where('month', $row->month)->first());


		}
		return view('charts.bar_graph', $data);
	}

	public function new_age_breakdown()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw($this->new_art_query())
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$rows2 = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw($this->former_new_art_query())
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

		$t = round(($target->total / 12), 2);

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Below 1";
		$data['outcomes'][1]['name'] = "Below 15";
		$data['outcomes'][2]['name'] = "Above 15";
		$data['outcomes'][3]['name'] = "Monthly Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "spline";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][3]['tooltip'] = array("valueSuffix" => ' ');

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->below_1 + $rows2[$key]->below_1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below_10 + $row->below_15 + $rows2[$key]->below_15;
			$data["outcomes"][2]["data"][$key] = (int) $row->below_20 + $row->below_25 + $row->above_25 + $rows2[$key]->above_15;
			$data["outcomes"][3]["data"][$key] = $t;
		}
		return view('charts.bar_graph', $data);		
	}



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
		$data['outcomes'][3]['type'] = "spline";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][3]['tooltip'] = array("valueSuffix" => ' ');

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->below_1 + $rows2[$key]->below_1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below_10 + $row->below_15 + $rows2[$key]->below_15;
			$data["outcomes"][2]["data"][$key] = (int) $row->below_20 + $row->below_25 + $row->above_25 + $rows2[$key]->above_15;
			$data["outcomes"][3]["data"][$key] = (int) $target->total;
		}
		return view('charts.bar_graph', $data);
	}

	public function enrolled_age_breakdown()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw($this->enrolled_art_query())
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$rows2 = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw($this->former_age_enrolled_query())
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Below 1";
		$data['outcomes'][1]['name'] = "Below 15";
		$data['outcomes'][2]['name'] = "Above 15";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->below_1 + $rows2[$key]->below_1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below_10 + $row->below_15 + $rows2[$key]->below_15;
			$data["outcomes"][2]["data"][$key] = (int) $row->below_20 + $row->below_25 + $row->above_25 + $rows2[$key]->above_15;
		}
		return view('charts.bar_graph', $data);
	}

	public function new_art()
	{		
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$sql = $q['select_query'] . ", " . $this->new_art_query();		

		$data['rows'] = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$sql = $q['select_query'] . ", " . $this->former_new_art_query();	

		$data['others'] = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['div'] = str_random(15);

		// return view('combined.art_totals', $data);
	}




}
