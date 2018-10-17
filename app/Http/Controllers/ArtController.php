<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class ArtController extends Controller
{

	public function treatment()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();	

		$newtx = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw(" SUM(`new_total`) AS `new_art` ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$date_query = Lookup::year_month_query(1);	
		$data['recent_name'] = Lookup::year_month_name();	

		$cutx = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw(" SUM(`current_total`) AS `current_art` ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->first();

		$date_query = Lookup::year_month_query(2);	
		$data['current_name'] = Lookup::year_month_name();	

		$cutx_old = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw(" SUM(`current_total`) AS `current_art` ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
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

		$data['div'] = str_random(15);

		$data['current_art_recent'] = $cutx->current_art;
		$data['current_art'] = $cutx_old->current_art;
		$data['new_art'] = $newtx->new_art;

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
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->when(true, $this->get_callback('total'))
			->whereRaw($date_query)
			->get();

		$start_art_new = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->whereRaw("`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026` > 0")
			->when(true, $this->get_callback('total'))
			->whereRaw($date_query)
			->get();

		$start_art_old = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->whereRaw("`total_starting_on_art` > 0")
			->when(true, $this->get_callback('total'))
			->whereRaw($date_query)
			->get();

		$current_art_new = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->whereRaw("`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0")
			->when(true, $this->get_callback('total'))
			->whereRaw($date_query)
			->get();

		$current_art_old = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->whereRaw("`total_currently_on_art` > 0")
			->when(true, $this->get_callback('total'))
			->whereRaw($date_query)
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

		$old_table = "`d_care_and_treatment`";
		$new_table = "`d_hiv_and_tb_treatment`";

		$old_column = "`total_starting_on_art`";
		$new_column = "`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`";

		$old_column_cu = "`total_currently_on_art`";
		$new_column_cu = "`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`";

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) Lookup::get_val($row, $start_art_old, 'total');
			$data["outcomes"][1]["data"][$key] = (int) Lookup::get_val($row, $start_art_new, 'total');
			$data["outcomes"][2]["data"][$key] = (int) Lookup::get_val($row, $current_art_old, 'total');
			$data["outcomes"][3]["data"][$key] = (int) Lookup::get_val($row, $current_art_new, 'total');

			$params = Lookup::duplicate_parameters($row);			

			$duplicate_new = DB::select(
				DB::raw("CALL `proc_get_double_reporting`('{$old_table}', '{$new_table}', '{$old_column}', '{$new_column}', \"{$divisions_query}\", \"{$date_query}\", '{$params[0]}', '{$params[1]}', '{$params[2]}', '{$params[3]}');"));

			$duplicate_cu = DB::select(
				DB::raw("CALL `proc_get_double_reporting`('{$old_table}', '{$new_table}', '{$old_column_cu}', '{$new_column_cu}', \"{$divisions_query}\", \"{$date_query}\", '{$params[0]}', '{$params[1]}', '{$params[2]}', '{$params[3]}');"));

			$data["outcomes"][4]["data"][$key] = (int) ($duplicate_new[0]->total ?? 0);
			$data["outcomes"][5]["data"][$key] = (int) ($duplicate_cu[0]->total ?? 0);
		}
		return view('charts.bar_graph', $data);
	}

	public function current_age_breakdown()
	{
		$date_query = Lookup::date_query();
		$groupby = session('filter_groupby', 1);

		if($groupby != 12) $date_query = Lookup::year_month_query();

		$sql = "
			SUM(current_below1) AS below1,
			(SUM(current_below10) + SUM(current_below15_m) + SUM(current_below15_f)) AS below15,
			(SUM(current_below20_m) + SUM(current_below20_f) + SUM(current_below25_m) + SUM(current_below25_f) + SUM(current_above25_m) + SUM(current_above25_f)) AS above15
		";	

		$rows = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback('above15'))
			->whereRaw($date_query)
			->get();

		$rows3 = DB::table('d_regimen_totals')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_regimen_totals.facility')
			->selectRaw("(SUM(d_regimen_totals.art) + SUM(pmtct)) AS total ")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->get();

		$date_query = Lookup::date_query(true);
		$target_obj = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`) AS `total`")
			->when(true, $this->target_callback())
			->get();

		$groupby = session('filter_groupby', 1);
		// $divisor = Lookup::get_target_divisor();
		$divisor = 1;

		if($groupby > 9){
			$t = $target_obj->first()->total;
			$target = round(($t / $divisor), 2);
		}

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

		$rows = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback('above15'))
			->whereRaw($date_query)
			->get();

		$rows3 = DB::table('m_testing')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_testing.facility')
			->selectRaw("SUM(positive_total) AS total ")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->get();

		$date_query = Lookup::date_query(true);
		$target_obj = DB::table('t_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026`) AS `total`")
			->when(true, $this->target_callback())
			->get();

		$groupby = session('filter_groupby', 1);
		$divisor = Lookup::get_target_divisor();

		if($groupby > 9){
			$t = $target_obj->first()->total;
			$target = round(($t / $divisor), 2);
		}

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Below 1";
		$data['outcomes'][1]['name'] = "Below 15";
		$data['outcomes'][2]['name'] = "Above 15";
		$data['outcomes'][3]['name'] = "Positive Tests";
		$data['outcomes'][4]['name'] = "Target";

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

		$rows = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback('above15'))
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Below 1";
		$data['outcomes'][1]['name'] = "Below 15";
		$data['outcomes'][2]['name'] = "Above 15";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
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

		$data['rows'] = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback('above15'))
			->whereRaw($date_query)
			->get();

		return view('tables.art_totals', $data);
	}


	public function current_art()
	{
		$data = Lookup::table_data();
		$date_query = Lookup::date_query();
		$groupby = session('filter_groupby', 1);

		if($groupby != 12) $date_query = Lookup::year_month_query();

		$sql = "
			SUM(current_below1) AS below1,
			(SUM(current_below10) + SUM(current_below15_m) + SUM(current_below15_f)) AS below15,
			(SUM(current_below20_m) + SUM(current_below20_f) + SUM(current_below25_m) + SUM(current_below25_f) + SUM(current_above25_m) + SUM(current_above25_f)) AS above15,
			SUM(current_total) AS reported_total,
			(SUM(current_below1) + SUM(current_below10) + SUM(current_below15_m) + SUM(current_below15_f) + SUM(current_below20_m) + SUM(current_below20_f) + SUM(current_below25_m) + SUM(current_below25_f) + SUM(current_above25_m) + SUM(current_above25_f)) AS actual_total			
		";	

		$data['rows'] = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback('above15'))
			->whereRaw($date_query)
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


		$data['rows'] = DB::table('apidb.vl_site_suppression_datim')
			->join('hcm.view_facilitys', 'view_facilitys.id', '=', 'vl_site_suppression_datim.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback('total'))
			->get();

		return view('tables.current_suppression', $data);
	}




}
