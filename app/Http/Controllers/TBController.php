<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class TBController extends Controller
{

	public function known_status()
	{
		$date_query = Lookup::date_query();
    	$groupby = session('filter_groupby', 1);

		$rows = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`tb_cases_known_positive(kps)_hv03-077`) as pos, SUM(`tb_known_status_hv03-079`) AS total ")
			->when(true, $this->get_callback('total'))
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positive";
		$data['outcomes'][1]['name'] = "Negative";
		$data['outcomes'][2]['name'] = "Positivity";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		if($groupby < 10){
			$data['outcomes'][2]['lineWidth'] = 0;
			$data['outcomes'][2]['marker'] = ['enabled' => true, 'radius' => 4];
			$data['outcomes'][2]['states'] = ['hover' => ['lineWidthPlus' => 0]];
		}

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			if($row->total < $row->pos) $row->total += $row->pos;

			$data["outcomes"][0]["data"][$key] = (int) $row->pos;
			$data["outcomes"][1]["data"][$key] = (int) $row->total - $row->pos;

			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($row->pos, $row->total);

		}
		return view('charts.dual_axis', $data);
	}

	public function newly_tested()
	{
		$date_query = Lookup::date_query();
    	$groupby = session('filter_groupby', 1);

		$rows = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("SUM(`tb_new_hiv_positive_hv03-080`) as pos, SUM(`tb_cases_tested_hiv_hv03-078`) AS total ")
			->when(true, $this->get_callback('total'))
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positive";
		$data['outcomes'][1]['name'] = "Negative";
		$data['outcomes'][2]['name'] = "Positivity";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		if($groupby < 10){
			$data['outcomes'][2]['lineWidth'] = 0;
			$data['outcomes'][2]['marker'] = ['enabled' => true, 'radius' => 4];
			$data['outcomes'][2]['states'] = ['hover' => ['lineWidthPlus' => 0]];
		}

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			if($row->total < $row->pos) $row->total += $row->pos;

			$data["outcomes"][0]["data"][$key] = (int) $row->pos;
			$data["outcomes"][1]["data"][$key] = (int) $row->total - $row->pos;

			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($row->pos, $row->total);

		}
		return view('charts.dual_axis', $data);
	}


	

	public function tb_screening()
	{		
		$date_query = Lookup::date_query();
		$data = Lookup::table_data();

		$sql = "SUM(`tb_screened_below1`) AS below1, SUM(`tb_screened_below10`) AS below10, SUM(`tb_screened_below15`) AS below15, SUM(`tb_screened_below20`) AS below20, SUM(`tb_screened_below25`) AS below25, SUM(`tb_screened_above25`) AS above25, 
			SUM(`tb_screened_total`) AS total
		 ";

		$data['rows'] = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback('total'))
			->whereRaw($date_query)
			->get();

		$data['paragraph'] = "The TB screened data from the new form is in the form above. However, the data from the old form is only above and below 15 years. In combining the data, below15 in the old form was merged with 10-14 and above 15 was merged with &gt;25.";

		return view('tables.circumcision_summary', $data);
	}


	public function ipt()
	{		
		$date_query = Lookup::date_query();
		$data = Lookup::table_data();

		$sql = "SUM(`start_ipt_<1_hv03-059`) AS below1, SUM(`start_ipt_1-9_hv03-060`) AS below10, SUM(`start_ipt_10-14_hv03-061`) AS below15, SUM(`start_ipt_15-19_hv03-062`) AS below20, SUM(`start_ipt_20-24_hv03-063`) AS below25, SUM(`start_ipt_25pos_hv03-064`) AS above25, 
			SUM(`start_ipt_total_hv03-065`) AS total
		 ";

		$data['rows'] = DB::table('d_hiv_and_tb_treatment')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback('total'))
			->whereRaw($date_query)
			->get();

		return view('tables.circumcision_summary', $data);
	}
}
