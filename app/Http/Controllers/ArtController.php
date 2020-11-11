<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class ArtController extends Controller
{
	private $my_table = 'm_art';

	public function treatment()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();	

		$newtx = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw(" SUM(`new_total`) AS `new_art`, COUNT(DISTINCT view_facilities.id) as reported ")
			->where('new_total', '>', 0)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$date_query = Lookup::year_month_query(1);	
		$data['recent_name'] = Lookup::year_month_name();	

		$cutx = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw(" SUM(`current_total`) AS `current_art`, COUNT(DISTINCT view_facilities.id) as reported ")
			->where('current_total', '>', 0)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$date_query = Lookup::year_month_query(2);	
		$data['current_name'] = Lookup::year_month_name();	

		$cutx_old = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw(" SUM(`current_total`) AS `current_art`, COUNT(DISTINCT view_facilities.id) as reported ")
			->where('current_total', '>', 0)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$date_query = Lookup::date_query(true);
		$target = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilities', 'view_facilities.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `current`, 
							SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) AS `new_art`")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::active_partner_query())
			->first();

		$data['target'] = $target;

		$data['div'] = str_random(15);

		$data['current_art_recent'] = $cutx->current_art;
		$data['current_art'] = $cutx_old->current_art;
		$data['new_art'] = $newtx->new_art;

		$data['current_reported_recent'] = $cutx->reported;
		$data['current_reported'] = $cutx_old->reported;
		$data['new_reported'] = $newtx->reported;

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

		$rows = DB::table('d_hiv_and_tb_treatment')
			->when(true, $this->get_joins_callback('d_hiv_and_tb_treatment'))
			->selectRaw("COUNT(DISTINCT facility) as total")
			->when(true, $this->get_callback('total'))
			->get();

		$start_art_new = DB::table('d_hiv_and_tb_treatment')
			->when(true, $this->get_joins_callback('d_hiv_and_tb_treatment'))
			->selectRaw("COUNT(DISTINCT facility) as total")
			->whereRaw("`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026` > 0")
			->when(true, $this->get_callback('total'))
			->get();

		$start_art_old = DB::table('d_care_and_treatment')
			->when(true, $this->get_joins_callback('d_care_and_treatment'))
			->selectRaw("COUNT(DISTINCT facility) as total")
			->whereRaw("`total_starting_on_art` > 0")
			->when(true, $this->get_callback('total'))
			->get();

		$current_art_new = DB::table('d_hiv_and_tb_treatment')
			->when(true, $this->get_joins_callback('d_hiv_and_tb_treatment'))
			->selectRaw("COUNT(DISTINCT facility) as total")
			->whereRaw("`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0")
			->when(true, $this->get_callback('total'))
			->get();

		$current_art_old = DB::table('d_care_and_treatment')
			->when(true, $this->get_joins_callback('d_care_and_treatment'))
			->selectRaw("COUNT(DISTINCT facility) as total")
			->whereRaw("`total_currently_on_art` > 0")
			->when(true, $this->get_callback('total'))
			->get();

		$data['div'] = str_random(15);

		Lookup::bars($data, ["New tx old form", "New tx new form", "Current tx old form", "Current tx new form", "Reporting New tx twice", "Reporting Current tx twice"], "spline");

		$old_table = "`d_care_and_treatment`";
		$new_table = "`d_hiv_and_tb_treatment`";

		$old_column = "`total_starting_on_art`";
		$new_column = "`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`";

		$old_column_cu = "`total_currently_on_art`";
		$new_column_cu = "`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`";

		$key = 0;

		foreach ($rows as $row) {

			$a = (int) Lookup::get_val($row, $start_art_old, 'total');
			$b = (int) Lookup::get_val($row, $start_art_new, 'total');
			$c = (int) Lookup::get_val($row, $current_art_old, 'total');
			$d = (int) Lookup::get_val($row, $current_art_new, 'total');

			if(!$a && !$b && !$c && !$d) continue;

			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) Lookup::get_val($row, $start_art_old, 'total');
			$data["outcomes"][1]["data"][$key] = (int) Lookup::get_val($row, $start_art_new, 'total');
			$data["outcomes"][2]["data"][$key] = (int) Lookup::get_val($row, $current_art_old, 'total');
			$data["outcomes"][3]["data"][$key] = (int) Lookup::get_val($row, $current_art_new, 'total');

			$params = Lookup::duplicate_parameters($row);	

			$params[0] = str_replace('view_facilities.id', 'f.id', $params[0]);		

			$duplicate_new = DB::select(
				DB::raw("CALL `proc_get_double_reporting`('{$old_table}', '{$new_table}', '{$old_column}', '{$new_column}', \"{$divisions_query}\", \"{$date_query}\", '{$params[0]}', '{$params[1]}', '{$params[2]}', '{$params[3]}');"));

			$duplicate_cu = DB::select(
				DB::raw("CALL `proc_get_double_reporting`('{$old_table}', '{$new_table}', '{$old_column_cu}', '{$new_column_cu}', \"{$divisions_query}\", \"{$date_query}\", '{$params[0]}', '{$params[1]}', '{$params[2]}', '{$params[3]}');"));

			$data["outcomes"][4]["data"][$key] = (int) ($duplicate_new[0]->total ?? 0);
			$data["outcomes"][5]["data"][$key] = (int) ($duplicate_cu[0]->total ?? 0);

			$key++;
		}
		return view('charts.bar_graph', $data);
	}

	public function current_age_breakdown()
	{
		$q = Lookup::groupby_query();
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
        $unshowable = Lookup::get_unshowable();
		$groupby = session('filter_groupby', 1);

		if($groupby != 12) $date_query = Lookup::year_month_query();

		$sql = "
			SUM(current_below1) AS below1,
			(SUM(current_below10) + SUM(current_below15_m) + SUM(current_below15_f)) AS below15,
			(SUM(current_below20_m) + SUM(current_below20_f) + SUM(current_below25_m) + SUM(current_below25_f) + SUM(current_above25_m) + SUM(current_above25_f)) AS above15
		";	

		// DB::enableQueryLog();

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($q['select_query'] . ", " . $sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupby($q['group_array'][0])
			->when(sizeof($q['group_array']) == 2, function($query) use($q){
				return $query->groupBy($q['group_array'][1]);
			})
			->when(($groupby < 10), function($query) use($groupby) {
				if($groupby == 5) $query->whereNotIn('view_facilities.id', $unshowable);
                if($groupby == 1) $query->where('partner', '!=', 69);
				return $query->orderBy('above15', 'desc');
			})
			->get();

		// return DB::getQueryLog();

		// dd($rows);

		$rows3 = DB::table('d_regimen_totals')
			->when(true, $this->get_joins_callback('d_regimen_totals'))
			->selectRaw($q['select_query'] . ", " . "(SUM(d_regimen_totals.art) + SUM(pmtct)) AS total ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupby($q['group_array'][0])
			->when(sizeof($q['group_array']) == 2, function($query) use($q){
				return $query->groupBy($q['group_array'][1]);
			})
			->get();

		$date_query = Lookup::date_query(true);
		$target_obj = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilities', 'view_facilities.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `total`")
			->when(true, $this->target_callback())
			->whereRaw(Lookup::active_partner_query())
			->get();

		// $divisor = Lookup::get_target_divisor();
		$divisor = 1;

		if($groupby > 9){
			$t = $target_obj->first()->total;
			$target = round(($t / $divisor), 2);
		}

		$data['div'] = str_random(15);

		Lookup::bars($data, ["Below 1", "Below 15", "Above 15", "MOH 729 Current tx Total", "Target"], "column");

		$data['outcomes'][0]['stack'] = 'current_art';
		$data['outcomes'][1]['stack'] = 'current_art';
		$data['outcomes'][2]['stack'] = 'current_art';
		$data['outcomes'][3]['stack'] = 'moh_729';

		Lookup::splines($data, [4]);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->below1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below15;
			$data["outcomes"][2]["data"][$key] = (int) $row->above15;

			$data["outcomes"][3]["data"][$key]  = (int) Lookup::get_val($row, $rows3, 'total');

			if(isset($target)) $data["outcomes"][4]["data"][$key] = $target;
			else{				
				$t = $target_obj->where('div_id', $row->div_id)->first()->total ?? 0;
				$data["outcomes"][4]["data"][$key] = round(($t / $divisor), 2);
			}
		}
		return view('charts.bar_graph', $data);
	}

	public function new_age_breakdown()
	{
		$date_query = Lookup::date_query();

		$sql = "
			SUM(new_below1) AS below1,
			(SUM(new_below10) + SUM(new_below15_m) + SUM(new_below15_f)) AS below15,
			(SUM(new_below20_m) + SUM(new_below20_f) + SUM(new_below25_m) + SUM(new_below25_f) + SUM(new_above25_m) + SUM(new_above25_f)) AS above15
		";	

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('above15'))
			->get();

		$rows3 = DB::table('m_testing')
			->when(true, $this->get_joins_callback('m_testing'))
			->selectRaw("SUM(positive_total) AS total ")
			->when(true, $this->get_callback())
			->get();

		$date_query = Lookup::date_query(true);
		$target_obj = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilities', 'view_facilities.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) AS `total`")
			->when(true, $this->target_callback())
			->whereRaw(Lookup::active_partner_query())
			->get();

		$groupby = session('filter_groupby', 1);
		$divisor = Lookup::get_target_divisor();

		if($groupby > 9){
			$t = $target_obj->first()->total;
			$target = round(($t / $divisor), 2);
		}

		$data['div'] = str_random(15);

		Lookup::bars($data, ["Below 1", "Below 15", "Above 15", "Positive Tests", "Target"], "column");

		$data['outcomes'][0]['stack'] = 'new_art';
		$data['outcomes'][1]['stack'] = 'new_art';
		$data['outcomes'][2]['stack'] = 'new_art';
		$data['outcomes'][3]['stack'] = 'positives';

		Lookup::splines($data, [4]);

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->below1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below15;
			$data["outcomes"][2]["data"][$key] = (int) $row->above15;

			$data["outcomes"][3]["data"][$key]  = (int) Lookup::get_val($row, $rows3, 'total');

			if(isset($target)) $data["outcomes"][4]["data"][$key] = $target;
			else{				
				$t = $target_obj->where('div_id', $row->div_id)->first()->total ?? 0;
				$data["outcomes"][4]["data"][$key] = round(($t / $divisor), 2);
			}
		}
		return view('charts.bar_graph', $data);
	}

	public function enrolled_age_breakdown()
	{
		$date_query = Lookup::date_query();

		$sql = "
			SUM(enrolled_below1) AS below1,
			(SUM(enrolled_below10) + SUM(enrolled_below15_m) + SUM(enrolled_below15_f)) AS below15,
			(SUM(enrolled_below20_m) + SUM(enrolled_below20_f) + SUM(enrolled_below25_m) + SUM(enrolled_below25_f) + SUM(enrolled_above25_m) + SUM(enrolled_above25_f)) AS above15
		";	

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('above15'))
			->get();

		$data['div'] = str_random(15);

		Lookup::bars($data, ["Below 1", "Below 15", "Above 15"], "column");
		
		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->below1;
			$data["outcomes"][1]["data"][$key] = (int) $row->below15;
			$data["outcomes"][2]["data"][$key] = (int) $row->above15;
		}
		return view('charts.bar_graph', $data);
	}

	public function new_art()
	{
		$date_query = Lookup::date_query();
		$data = Lookup::table_data();

		$sql = "
			SUM(new_below1) AS below1,
			(SUM(new_below10) + SUM(new_below15_m) + SUM(new_below15_f)) AS below15,
			(SUM(new_below20_m) + SUM(new_below20_f) + SUM(new_below25_m) + SUM(new_below25_f) + SUM(new_above25_m) + SUM(new_above25_f)) AS above15,
			SUM(new_total) AS reported_total,
			(SUM(new_below1) + SUM(new_below10) + SUM(new_below15_m) + SUM(new_below15_f) + SUM(new_below20_m) + SUM(new_below20_f) + SUM(new_below25_m) + SUM(new_below25_f) + SUM(new_above25_m) + SUM(new_above25_f)) AS actual_total
		";	

		$data['rows'] = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('above15'))
			->get();

		return view('tables.art_totals', $data);
	}


	public function current_art()
	{
		$data = Lookup::table_data();
		$date_query = Lookup::date_query();
		$groupby = session('filter_groupby', 1);

		if($groupby != 12) $date_query = Lookup::year_month_query();

		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();
        $unshowable = Lookup::get_unshowable();

		$sql = $q['select_query'] . ", " . "
			SUM(current_below1) AS below1,
			(SUM(current_below10) + SUM(current_below15_m) + SUM(current_below15_f)) AS below15,
			(SUM(current_below20_m) + SUM(current_below20_f) + SUM(current_below25_m) + SUM(current_below25_f) + SUM(current_above25_m) + SUM(current_above25_f)) AS above15,
			SUM(current_total) AS reported_total,
			(SUM(current_below1) + SUM(current_below10) + SUM(current_below15_m) + SUM(current_below15_f) + SUM(current_below20_m) + SUM(current_below20_f) + SUM(current_below25_m) + SUM(current_below25_f) + SUM(current_above25_m) + SUM(current_above25_f)) AS actual_total			
		";	

		$data['rows'] = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_array'][0])
			->when(sizeof($q['group_array']) == 2, function($query) use($q){
				return $query->groupBy($q['group_array'][1]);
			})
			->when(($groupby < 10), function($query) use($groupby) {
				if($groupby == 5) $query->whereNotIn('view_facilities.id', $unshowable);
                if($groupby == 1) $query->where('partner', '!=', 69);
				return $query->orderBy('reported_total', 'desc');
			})
			->get();
		$data['period_name'] = Lookup::year_month_name();

		return view('tables.art_totals', $data);
	}

	public function current_suppression()
	{
		$data = Lookup::table_data();
		$groupby = session('filter_groupby', 1);

		if($groupby > 9) return null;

		$sql = "
			SUM(`below1_m_sup`) AS below1_m_sup,
			SUM(`below1_f_sup`) AS below1_f_sup,
			SUM(`below1_u_sup`) AS below1_u_sup,
			SUM(`below1_m_nonsup`) AS below1_m_nonsup,
			SUM(`below1_f_nonsup`) AS below1_f_nonsup,
			SUM(`below1_u_nonsup`) AS below1_u_nonsup,

			SUM(`below5_m_sup`) AS below5_m_sup,
			SUM(`below5_f_sup`) AS below5_f_sup,
			SUM(`below5_u_sup`) AS below5_u_sup,
			SUM(`below5_m_nonsup`) AS below5_m_nonsup,
			SUM(`below5_f_nonsup`) AS below5_f_nonsup,
			SUM(`below5_u_nonsup`) AS below5_u_nonsup,

			SUM(`below10_m_sup`) AS below10_m_sup,
			SUM(`below10_f_sup`) AS below10_f_sup,
			SUM(`below10_u_sup`) AS below10_u_sup,
			SUM(`below10_m_nonsup`) AS below10_m_nonsup,
			SUM(`below10_f_nonsup`) AS below10_f_nonsup,
			SUM(`below10_u_nonsup`) AS below10_u_nonsup,

			SUM(`below15_m_sup`) AS below15_m_sup,
			SUM(`below15_f_sup`) AS below15_f_sup,
			SUM(`below15_u_sup`) AS below15_u_sup,
			SUM(`below15_m_nonsup`) AS below15_m_nonsup,
			SUM(`below15_f_nonsup`) AS below15_f_nonsup,
			SUM(`below15_u_nonsup`) AS below15_u_nonsup,

			SUM(`below20_m_sup`) AS below20_m_sup,
			SUM(`below20_f_sup`) AS below20_f_sup,
			SUM(`below20_u_sup`) AS below20_u_sup,
			SUM(`below20_m_nonsup`) AS below20_m_nonsup,
			SUM(`below20_f_nonsup`) AS below20_f_nonsup,
			SUM(`below20_u_nonsup`) AS below20_u_nonsup,

			SUM(`below25_m_sup`) AS below25_m_sup,
			SUM(`below25_f_sup`) AS below25_f_sup,
			SUM(`below25_u_sup`) AS below25_u_sup,
			SUM(`below25_m_nonsup`) AS below25_m_nonsup,
			SUM(`below25_f_nonsup`) AS below25_f_nonsup,
			SUM(`below25_u_nonsup`) AS below25_u_nonsup,

			SUM(`below30_m_sup`) AS below30_m_sup,
			SUM(`below30_f_sup`) AS below30_f_sup,
			SUM(`below30_u_sup`) AS below30_u_sup,
			SUM(`below30_m_nonsup`) AS below30_m_nonsup,
			SUM(`below30_f_nonsup`) AS below30_f_nonsup,
			SUM(`below30_u_nonsup`) AS below30_u_nonsup,

			SUM(`below35_m_sup`) AS below35_m_sup,
			SUM(`below35_f_sup`) AS below35_f_sup,
			SUM(`below35_u_sup`) AS below35_u_sup,
			SUM(`below35_m_nonsup`) AS below35_m_nonsup,
			SUM(`below35_f_nonsup`) AS below35_f_nonsup,
			SUM(`below35_u_nonsup`) AS below35_u_nonsup,

			SUM(`below40_m_sup`) AS below40_m_sup,
			SUM(`below40_f_sup`) AS below40_f_sup,
			SUM(`below40_u_sup`) AS below40_u_sup,
			SUM(`below40_m_nonsup`) AS below40_m_nonsup,
			SUM(`below40_f_nonsup`) AS below40_f_nonsup,
			SUM(`below40_u_nonsup`) AS below40_u_nonsup,

			SUM(`below45_m_sup`) AS below45_m_sup,
			SUM(`below45_f_sup`) AS below45_f_sup,
			SUM(`below45_u_sup`) AS below45_u_sup,
			SUM(`below45_m_nonsup`) AS below45_m_nonsup,
			SUM(`below45_f_nonsup`) AS below45_f_nonsup,
			SUM(`below45_u_nonsup`) AS below45_u_nonsup,

			SUM(`below50_m_sup`) AS below50_m_sup,
			SUM(`below50_f_sup`) AS below50_f_sup,
			SUM(`below50_u_sup`) AS below50_u_sup,
			SUM(`below50_m_nonsup`) AS below50_m_nonsup,
			SUM(`below50_f_nonsup`) AS below50_f_nonsup,
			SUM(`below50_u_nonsup`) AS below50_u_nonsup,

			SUM(`above50_m_sup`) AS above50_m_sup,
			SUM(`above50_f_sup`) AS above50_f_sup,
			SUM(`above50_u_sup`) AS above50_u_sup,
			SUM(`above50_m_nonsup`) AS above50_m_nonsup,
			SUM(`above50_f_nonsup`) AS above50_f_nonsup,
			SUM(`above50_u_nonsup`) AS above50_u_nonsup,

			SUM(`total_m_sup`) AS total_m_sup,
			SUM(`total_f_sup`) AS total_f_sup,
			SUM(`total_u_sup`) AS total_u_sup,
			SUM(`total_m_nonsup`) AS total_m_nonsup,
			SUM(`total_f_nonsup`) AS total_f_nonsup,
			SUM(`total_u_nonsup`) AS total_u_nonsup,

			(SUM(`total_m_sup`) + SUM(`total_f_sup`) + SUM(`total_u_sup`) + SUM(`total_m_nonsup`) + SUM(`total_f_nonsup`) + SUM(`total_u_nonsup`)) AS total
		";

		// DB::enableQueryLog();

		$data['rows'] = DB::table('apidb.vl_site_suppression_datim')
			->join('hcm.view_facilitys', 'view_facilitys.id', '=', 'vl_site_suppression_datim.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback_no_dates('total'))
			->get();

		// $data['query_log'] = DB::getQueryLog();

		return view('tables.current_suppression', $data);
	}




}
