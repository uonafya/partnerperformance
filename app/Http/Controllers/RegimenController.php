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
			->addSelect('year', 'month')
			->whereRaw("`d_regimen_totals`.`art` > 0")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$current_art_new = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->addSelect('year', 'month')
			->whereRaw("`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
			->get();

		$current_art_old = DB::table('d_care_and_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_care_and_treatment.facility')
			->selectRaw("COUNT(facility) as total")
			->addSelect('year', 'month')
			->whereRaw("`total_currently_on_art` > 0")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy('year', 'month')
			->orderBy('year', 'asc')
			->orderBy('month', 'asc')
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
			$data['categories'][$key] = Lookup::get_category($row->year, $row->month);
			$data["outcomes"][0]["data"][$key] = (int) $row->total;
			$data["outcomes"][1]["data"][$key] = $this->check_null($current_art_old->where('year', $row->year)->where('month', $row->month)->first());
			$data["outcomes"][2]["data"][$key] = $this->check_null($current_art_new->where('year', $row->year)->where('month', $row->month)->first());

			$duplicate_reporting = DB::select(
				DB::raw("CALL `proc_get_reporting_twice`('{$old_table}', '{$new_table}', '{$old_column}', '{$new_column}', '{$divisions_query}', {$row->year}, {$row->month});"));

			$data["outcomes"][3]["data"][$key] = (int) ($duplicate_reporting[0]->total ?? 0);

		}
		return view('charts.bar_graph', $data);
	}

	public function summary()
	{
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$sql_art = $q['select_query'] . ",  SUM(`qu`.`art`) AS `art` ";
		$sql_pmtct = $q['select_query'] . ", SUM(`qu`.`pmtct`) AS `pmtct` ";

		$subquery_art = "(
			SELECT facility, art
			FROM `d_regimen_totals` d
			RIGHT JOIN
			(
				SELECT MAX(`id`) AS max_id
				FROM `d_regimen_totals`
				WHERE {$date_query} AND art>0
				GROUP BY facility
			) s ON s.max_id=d.id

		) qu";

		$subquery_pmtct = "(
			SELECT facility, pmtct
			FROM `d_regimen_totals` d
			RIGHT JOIN
			(
				SELECT MAX(`id`) AS max_id
				FROM `d_regimen_totals`
				WHERE {$date_query} AND pmtct>0
				GROUP BY facility
			) s ON s.max_id=d.id
		) qu";


		$data['art_rows'] = DB::table(DB::raw($subquery_art))
			->join('view_facilitys', 'view_facilitys.id', '=', 'qu.facility')
			->selectRaw($sql_art)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();


		$data['pmtct_rows'] = DB::table(DB::raw($subquery_pmtct))
			->join('view_facilitys', 'view_facilitys.id', '=', 'qu.facility')
			->selectRaw($sql_pmtct)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['div'] = str_random(15);

		return view('combined.regimen_pmtct', $data);

	}


		// $c = " SELECT partner as div_id, partnername as name, SUM(`qu`.`art`) AS `art`
		// 	FROM (
		// 	SELECT facility, art
		// 	FROM `d_regimen_totals` d
		// 	RIGHT JOIN
		// 	(
		// 		SELECT MAX(`id`) AS max_id
		// 		FROM `d_regimen_totals`
		// 		WHERE {$date_query} AND art>0
		// 		GROUP BY facility
		// 	) s ON s.max_id=d.id

		// ) qu
		// JOIN view_facilitys ON view_facilitys.id=qu.facility
		// GROUP BY partner
		// ";


		// $subquery = "(
		// 	SELECT facility, MAX(`art`) AS `art`, MAX(`pmtct`) AS `pmtct`
		// 	FROM `d_regimen_totals`
		// 	JOIN `view_facilitys` ON `view_facilitys`.`id`=`d_regimen_totals`.`facility`
		// 	WHERE {$divisions_query} AND {$date_query}
		// 	GROUP BY facility
		// ) qu";

		// $test_sql = "(
		// 	SELECT * 
		// 	FROM *
		// 	RIGHT JOIN 
		// 	(
		// 		SELECT MAX(n.id) as max_id,
		// 		FROM d_care_and_treatment o
		// 		JOIN d_hiv_and_tb_treatment n ON n.id=o.id
		// 		WHERE {$divisions_query} AND {$date_query} AND (o.total_currently_on_art > 0 OR n.`on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038` > 0)
		// 		GROUP BY facility
		// 	)
		// ) qu";



}
