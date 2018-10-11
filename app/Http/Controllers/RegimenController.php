<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class RegimenController extends Controller
{
	public function reporting()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();

		$current_art_other = DB::table('d_regimen_totals')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_regimen_totals.facility')
			->selectRaw("COUNT(facility) as total")
			->whereRaw("`d_regimen_totals`.`art` > 0")
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

		$old_table = "`d_care_and_treatment`";
		$new_table = "`d_hiv_and_tb_treatment`";

		$old_column = "`total_currently_on_art`";
		$new_column = "`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038`";


		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Current tx MOH 729";
		$data['outcomes'][1]['name'] = "Current tx MOH 731";
		$data['outcomes'][2]['name'] = "Current tx MOH 731 rev. 2018";
		$data['outcomes'][3]['name'] = "Reporting on 731 old & new";

		$data['outcomes'][0]['type'] = "spline";
		$data['outcomes'][1]['type'] = "spline";
		$data['outcomes'][2]['type'] = "spline";
		$data['outcomes'][3]['type'] = "spline";

		foreach ($current_art_other as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->total;
			$data["outcomes"][1]["data"][$key] = (int) Lookup::get_val($row, $current_art_old, 'total');
			$data["outcomes"][2]["data"][$key] = (int) Lookup::get_val($row, $current_art_new, 'total');

			$params = Lookup::duplicate_parameters($row);			

			$duplicate_reporting = DB::select(
				DB::raw("CALL `proc_get_double_reporting`('{$old_table}', '{$new_table}', '{$old_column}', '{$new_column}', \"{$divisions_query}\", \"{$date_query}\", '{$params[0]}', '{$params[1]}', '{$params[2]}', '{$params[3]}');"));

			$data["outcomes"][3]["data"][$key] = (int) ($duplicate_reporting[0]->total ?? 0);

		}
		return view('charts.bar_graph', $data);
	}


	public function summary()
	{
		$data = Lookup::table_data();
		$date_query = Lookup::year_month_query();

		$data['rows'] = DB::table('d_regimen_totals')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_regimen_totals.facility')
			->selectRaw("SUM(art) as art, SUM(pmtct) as pmtct")
			->when(true, $this->get_callback('art'))
			->whereRaw($date_query)
			->get();

		$data['current_tx_date'] = Lookup::year_month_name();

		return view('tables.regimen_pmtct', $data);

	}
}