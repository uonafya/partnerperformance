<?php

namespace App\Http\Controllers\Former;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class ArtController extends Controller
{


	public function treatment()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$data['div'] = str_random(15);

		$sql = $this->current_art_query();		

		$new_n = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw(" SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) AS `new_art` ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$new_o = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw(" SUM(`total_starting_on_art`) AS `new_art`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();


		// $dup_new = DB::table('d_care_and_treatment')
		// 	->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
		// 	->selectRaw("SUM(`total_starting_on_art`) AS `new_art`")
		// 	->whereRaw($date_query)
		// 	->whereRaw("facility IN (
		// 		SELECT DISTINCT facility
		// 		FROM d_hiv_and_tb_treatment d JOIN view_facilitys f ON d.facility=f.id
		// 		WHERE  {$divisions_query} AND {$date_query} AND `start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026` > 0
		// 	)")
		// 	->first();


		$date_query = Lookup::year_month_query();	
		$data['current_name'] = Lookup::year_month_name();	

		$cu_n = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `current` ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$cu_o = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("SUM(`total_currently_on_art`) AS `current` ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$dup_current = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("SUM(`total_currently_on_art`) AS `current`")
			->whereRaw($date_query)
			->whereRaw("facility IN (
				SELECT DISTINCT facility
				FROM d_hiv_and_tb_treatment d JOIN view_facilitys f ON d.facility=f.id
				WHERE  {$divisions_query} AND {$date_query} AND `on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0
			)")
			->first();


		$date_query = Lookup::year_month_query(1);	
		$data['recent_name'] = Lookup::year_month_name();	

		$cu_n2 = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `current` ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$cu_o2 = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("SUM(`total_currently_on_art`) AS `current`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$dup_current2 = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("SUM(`total_currently_on_art`) AS `current`")
			->whereRaw($date_query)
			->whereRaw("facility IN (
				SELECT DISTINCT facility
				FROM d_hiv_and_tb_treatment d JOIN view_facilitys f ON d.facility=f.id
				WHERE  {$divisions_query} AND {$date_query} AND `on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0
			)")
			->first();

		$date_query = Lookup::date_query(true);
		$target = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `current`, 
							SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) AS `new_art`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$data['target'] = $target;

		$data['current_art_recent'] = $cu_n2->current + $cu_o2->current;
		$data['current_art'] = $cu_n->current + $cu_o->current;
		$data['new_art'] = $new_n->new_art + $new_o->new_art;

		if(is_object($dup_current2)) $data['current_art_recent'] -= $dup_current2->current; 
		if(is_object($dup_current)) $data['current_art'] -= $dup_current->current; 
		// if(is_object($dup_new)) $data['new_art'] -= $dup_new->new_art; 

		$data['current_completion_recent'] = Lookup::get_percentage($data['current_art_recent'], $target->current);
		$data['current_completion'] = Lookup::get_percentage($data['current_art'], $target->current);
		$data['new_completion'] = Lookup::get_percentage($data['new_art'], $target->new_art);

		$data['current_status_recent'] = Lookup::progress_status($data['current_completion_recent']);
		$data['current_status'] = Lookup::progress_status($data['current_completion']);
		$data['new_status'] = Lookup::progress_status($data['new_completion']);

		return view('combined.treatment', $data);
	}

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

		$start_art_new = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->addSelect('year', 'month')
			->whereRaw("`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026` > 0")
			->whereRaw($divisions_query)
			->whereRaw($date_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$start_art_old = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->addSelect('year', 'month')
			->whereRaw("`total_starting_on_art` > 0")
			->whereRaw($divisions_query)
			->whereRaw($date_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$current_art_new = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->addSelect('year', 'month')
			->whereRaw("`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0")
			->whereRaw($divisions_query)
			->whereRaw($date_query)
			->whereRaw($date_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$current_art_old = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->addSelect('year', 'month')
			->whereRaw("`total_currently_on_art` > 0")
			->whereRaw($divisions_query)
			->whereRaw($date_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();


		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "New tx old form";
		$data['outcomes'][1]['name'] = "New tx new form";
		$data['outcomes'][2]['name'] = "Current tx old form";
		$data['outcomes'][3]['name'] = "Current tx new form";

		$data['outcomes'][4]['name'] = "Reporting New tx twice";
		$data['outcomes'][5]['name'] = "Reporting Current tx twice";

		$data['outcomes'][0]['type'] = "spline";
		$data['outcomes'][1]['type'] = "spline";
		$data['outcomes'][2]['type'] = "spline";
		$data['outcomes'][3]['type'] = "spline";
		$data['outcomes'][4]['type'] = "spline";
		$data['outcomes'][5]['type'] = "spline";

		foreach ($dates as $key => $row) {
			if($row->year == date('Y') && $row->month == date('m')) break;
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			// $data["outcomes"][0]["data"][$key] = (int) $row->total;

			$data["outcomes"][0]["data"][$key] = $this->check_null($start_art_old->where('year', $row->year)->where('month', $row->month)->first());
			$data["outcomes"][1]["data"][$key] = $this->check_null($start_art_new->where('year', $row->year)->where('month', $row->month)->first());
			$data["outcomes"][2]["data"][$key] = $this->check_null($current_art_old->where('year', $row->year)->where('month', $row->month)->first());
			$data["outcomes"][3]["data"][$key] = $this->check_null($current_art_new->where('year', $row->year)->where('month', $row->month)->first());

			// DB::enableQueryLog();

			$double_starting = DB::table('d_hiv_and_tb_treatment')
							->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
							->selectRaw("COUNT(facility) as total")
							->whereRaw("`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026` > 0")
							->whereRaw($divisions_query)
							->where(['year' => $row->year, 'month' => $row->month])
							->whereRaw("facility IN (
								SELECT DISTINCT facility
								FROM d_care_and_treatment d JOIN view_facilitys f ON d.facility=f.id
								WHERE {$divisions_query} AND `total_starting_on_art` > 0 AND 
								year = {$row->year} AND month = {$row->month}
							)")
							->first();

			$double_current = DB::table('d_hiv_and_tb_treatment')
							->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
							->selectRaw("COUNT(facility) as total")
							->whereRaw("`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0")
							->whereRaw($divisions_query)
							->where(['year' => $row->year, 'month' => $row->month])
							->whereRaw("facility IN (
								SELECT DISTINCT facility
								FROM d_care_and_treatment d JOIN view_facilitys f ON d.facility=f.id
								WHERE  {$divisions_query} AND `total_currently_on_art` > 0 AND 
								year = {$row->year} AND month = {$row->month}
							)")
							->first();

	 		// return DB::getQueryLog();

			$data["outcomes"][4]["data"][$key] = is_object($double_starting) ? (int) $double_starting->total : 0;
			$data["outcomes"][5]["data"][$key] = is_object($double_current) ? (int) $double_current->total : 0;
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


		$rows3 = DB::table('d_regimen_totals')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_regimen_totals.facility')
			->selectRaw("SUM(d_regimen_totals.art) AS art, SUM(pmtct) AS pmtct ")
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
		$data['outcomes'][3]['name'] = "MOH 729 Current tx Total";
		$data['outcomes'][4]['name'] = "Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";
		$data['outcomes'][4]['type'] = "spline";

		$data['outcomes'][0]['stack'] = 'current_art';
		$data['outcomes'][1]['stack'] = 'current_art';
		$data['outcomes'][2]['stack'] = 'current_art';
		$data['outcomes'][3]['stack'] = 'moh_729';

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->below_1 + $rows2[$key]->below_1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below_10 + $row->below_15 + $rows2[$key]->below_15;
			$data["outcomes"][2]["data"][$key] = (int) $row->below_20 + $row->below_25 + $row->above_25 + $rows2[$key]->above_15;
			$data["outcomes"][3]["data"][$key] = (int) $rows3[$key]->art + $rows3[$key]->pmtct;

			$data["outcomes"][4]["data"][$key] = (int) $target->total;

			// $duplicate = DB::table('d_care_and_treatment')
			// 	->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			// 	->selectRaw($this->former_age_current_query())
			// 	->where(['year' => $row->year, 'month' => $row->month])
			// 	->whereRaw("facility IN (
			// 		SELECT DISTINCT facility
			// 		FROM d_hiv_and_tb_treatment d JOIN view_facilitys f ON d.facility=f.id
			// 		WHERE  {$divisions_query} AND `on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0 AND 
			// 		year = {$row->year} AND month = {$row->month}
			// 	)")
			// 	->first();

			// if(is_object($duplicate)){
			// 	$data["outcomes"][0]["data"][$key] -= $duplicate->below_1;
			// 	$data["outcomes"][1]["data"][$key] -= $duplicate->below_15;
			// 	$data["outcomes"][2]["data"][$key] -= $duplicate->above_15;
			// }

			$duplicate2 = DB::table('d_hiv_and_tb_treatment')
							->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
							->selectRaw($this->current_art_query())
							->whereRaw("`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0")
							->where(['year' => $row->year, 'month' => $row->month])
							->whereRaw("facility IN (
								SELECT DISTINCT facility
								FROM d_care_and_treatment d JOIN view_facilitys f ON d.facility=f.id
								WHERE  {$divisions_query} AND `total_currently_on_art` > 0 AND 
								year = {$row->year} AND month = {$row->month}
							)")
							->first();

			if(is_object($duplicate2)){
				$data["outcomes"][0]["data"][$key] -= $duplicate2->below_1;
				$data["outcomes"][1]["data"][$key] -= ($duplicate2->below_10 + $duplicate2->below_15);
				$data["outcomes"][2]["data"][$key] -= ($duplicate2->below_20 + $duplicate2->below_25 + $duplicate2->above_25);
			}
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

		$sql = "
			SUM(`tested_total_(sum_hv01-01_to_hv01-10)_hv01-10`) AS tests,
			SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos
		";

		$rows3 = DB::table('d_hiv_testing_and_prevention_services')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
			->selectRaw($sql)
			->addSelect('year', 'month')
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$sql = "
			SUM(`total_tested_hiv`) AS tests,
			SUM(`total_received_hivpos_results`) AS pos
		";

		$rows4 = DB::table('d_hiv_counselling_and_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_counselling_and_testing.facility')
			->selectRaw($sql)
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
		$data['outcomes'][3]['name'] = "Positive Tests";
		$data['outcomes'][4]['name'] = "Monthly Target";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";
		$data['outcomes'][4]['type'] = "spline";

		$data['outcomes'][0]['stack'] = 'new_art';
		$data['outcomes'][1]['stack'] = 'new_art';
		$data['outcomes'][2]['stack'] = 'new_art';
		$data['outcomes'][3]['stack'] = 'positives';

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->below_1 + $rows2[$key]->below_1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below_10 + $row->below_15 + $rows2[$key]->below_15;
			$data["outcomes"][2]["data"][$key] = (int) $row->below_20 + $row->below_25 + $row->above_25 + $rows2[$key]->above_15;

			$duplicate2 = DB::table('d_hiv_and_tb_treatment')
							->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
							->selectRaw($this->new_art_query())
							->whereRaw("`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0")
							->where(['year' => $row->year, 'month' => $row->month])
							->whereRaw("facility IN (
								SELECT DISTINCT facility
								FROM d_care_and_treatment d JOIN view_facilitys f ON d.facility=f.id
								WHERE  {$divisions_query} AND `total_starting_on_art` > 0 AND 
								year = {$row->year} AND month = {$row->month}
							)")
							->first();

			if(is_object($duplicate2)){
				$data["outcomes"][0]["data"][$key] -= $duplicate2->below_1;
				$data["outcomes"][1]["data"][$key] -= ($duplicate2->below_10 + $duplicate2->below_15);
				$data["outcomes"][2]["data"][$key] -= ($duplicate2->below_20 + $duplicate2->below_25 + $duplicate2->above_25);
			}

			$data["outcomes"][3]["data"][$key] = (int) $rows3[$key]->pos + $rows4[$key]->pos;

			$duplicate_pos = DB::table('d_hiv_testing_and_prevention_services')
							->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_testing_and_prevention_services.facility')
							->selectRaw("SUM(`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26`) AS pos")
							->whereRaw("`positive_total_(sum_hv01-18_to_hv01-27)_hv01-26` > 0")
							->where(['year' => $row->year, 'month' => $row->month])
							->whereRaw("facility IN (
								SELECT DISTINCT facility
								FROM d_hiv_counselling_and_testing d JOIN view_facilitys f ON d.facility=f.id
								WHERE  {$divisions_query} AND `total_received_hivpos_results` > 0 AND 
								year = {$row->year} AND month = {$row->month}
							)")
							->first();

			if(is_object($duplicate_pos)){
				$data["outcomes"][3]["data"][$key] -= $duplicate_pos->pos;
			}

			$data["outcomes"][4]["data"][$key] = $t;
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
        // ini_set("memory_limit", "-1");
        // ini_set('max_execution_time', 300);
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

		// $data['duplicates'] = DB::table('d_care_and_treatment')
		// 	->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
		// 	->selectRaw($sql)
		// 	->whereRaw($date_query)
		// 	->whereRaw("facility IN (
		// 		SELECT DISTINCT facility
		// 		FROM d_hiv_and_tb_treatment d JOIN view_facilitys f ON d.facility=f.id
		// 		WHERE  {$divisions_query} AND {$date_query} AND `start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026` > 0
		// 	)")
		// 	->groupBy($q['group_query'])
		// 	->get();

		$data['duplicates'] = DB::table('countys')->where('id', '<', 0)->get();

		$data['div'] = str_random(15);

		return view('combined.art_totals', $data);
	}

	public function current_art()
	{		
        // ini_set("memory_limit", "-1");
        // ini_set('max_execution_time', 300);
		$date_query = Lookup::year_month_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$sql = $q['select_query'] . ", " . $this->current_art_query();	

		// DB::enableQueryLog();	

		$data['rows'] = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();	

		// $data['duplicates'] = DB::table('d_hiv_and_tb_treatment')
		// 	->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
		// 	->selectRaw($sql)
		// 	->whereRaw($date_query)
		// 	->whereRaw("facility IN (
		// 		SELECT DISTINCT facility
		// 		FROM d_care_and_treatment d JOIN view_facilitys f ON d.facility=f.id
		// 		WHERE  {$divisions_query} AND {$date_query} AND `total_currently_on_art` > 0 
		// 	)")
		// 	->groupBy($q['group_query'])
		// 	->get();

		$sql = $q['select_query'] . ", " . $this->former_age_current_query();	

		$data['others'] = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['duplicates'] = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw("facility IN (
				SELECT DISTINCT facility
				FROM d_hiv_and_tb_treatment d JOIN view_facilitys f ON d.facility=f.id
				WHERE  {$divisions_query} AND {$date_query} AND `on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0
			)")
			->groupBy($q['group_query'])
			->get();

		// return DB::getQueryLog();

		// $data['duplicates'] = DB::select(
		// 		DB::raw("CALL `proc_get_duplicate_total`('{$old_table}', '{$new_table}', '{$old_column_tests}', '{$new_column_tests}', '{$divisions_query}', {$row->year}, {$row->month});"));

		$data['div'] = str_random(15);
		$data['period_name'] = Lookup::year_month_name();

		return view('combined.art_totals', $data);
	}




}
