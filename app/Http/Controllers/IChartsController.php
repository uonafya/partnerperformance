<?php

namespace App\Http\Controllers;

use App\Commons\Commons;
use App\Commons\get_hfr_sum;
use App\Commons\get_hfr_sum_prev;
use App\Commons\linkageServiceRoutine;
use App\Commons\testingServiceRoutine;
use App\Commons\tx_curr_oldServiceRoutines;
use App\Commons\tx_curr_trendServiceRoutine;
use App\Commons\tx_currServiceRoutine;
use App\Commons\tx_newServiceRoutine;
use App\HfrSubmission;
use App\Lookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class IChartsController extends Controller
{

	use Commons, get_hfr_sum,get_hfr_sum_prev;

	use testingServiceRoutine, linkageServiceRoutine,
	tx_curr_oldServiceRoutines, tx_currServiceRoutine,
	tx_newServiceRoutine, tx_curr_trendServiceRoutine;


    public function dual_axes_and_column()
	{

		$tests = HfrSubmission::columns(true, 'hts_tst'); 
		$pos = HfrSubmission::columns(true, 'hts_tst_pos');
		$sql = $this->get_hfr_sum($tests, 'tests') . ', ' . $this->get_hfr_sum($pos, 'pos');

		// dd($sql);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks_hfr($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('tests'))
			->get();

		// dd($rows);
		$data['div'] = str_random(15);
		$data['yAxis'] = "Total Number Tested";
		$data['yAxis2'] = "Yield (%)";
		$data['data_labels'] = true;
		$data['no_column_label'] = true;
		$data['suffix'] = '%';


		Lookup::bars($data, ["Positive", "Negative", "Yield"], "column", ["#ff0000", "#00ff00", "#3023ea"]);
		Lookup::splines($data, [2]);
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');
		Lookup::yAxis($data, 0, 1);

		$i=0;
		foreach ($rows as $row){
			if(!$row->tests) continue;

			$data['categories'][$i] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$i] = (int) $row->pos;
			$data["outcomes"][1]["data"][$i] = (int) ($row->tests - $row->pos);
			$data["outcomes"][2]["data"][$i] = Lookup::get_percentage($row->pos, $row->tests);
			$i++;
		}

		// return view('charts.dual_axis', $data);
		return view("iCharts.dual_axes_and_column", $data);
	}

	public function linkage()
	{
		Cache::forget("linkageServiceRoutine");

		Cache::rememberForever("linkageServiceRoutine", function(){
			return $this->linkageServiceRoutine();
		});

		$data = Cache::get("linkageServiceRoutine");

		return view('iCharts.dual_axes_and_column', $data);
	}
	
}
