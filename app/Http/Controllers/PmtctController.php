<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class PmtctController extends Controller
{

	public function haart()
	{
		$date_query = Lookup::date_query();

		$rows = DB::table('m_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_pmtct.facility')
			->selectRaw("SUM(haart_total) AS total")
			->when(true, $this->get_callback('total'))
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Patients";
		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->total;			
		}
		return view('charts.bar_graph', $data);
	}


	public function testing()
	{
		$date_query = Lookup::date_query();
    	$groupby = session('filter_groupby', 1);

		$rows = DB::table('m_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_pmtct.facility')
			->selectRaw("SUM(tested_pmtct) AS tests, SUM(total_new_positive_pmtct) AS pos")
			->when(true, $this->get_callback('tests'))
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positive Tests";
		$data['outcomes'][1]['name'] = "Negative Tests";
		$data['outcomes'][2]['name'] = "Positivity";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "spline";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		if($groupby < 10){
			$data['outcomes'][2]['lineWidth'] = 0;
			$data['outcomes'][2]['marker'] = ['enabled' => true, 'radius' => 4];
			$data['outcomes'][2]['states'] = ['hover' => ['lineWidthPlus' => 0]];
		}

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->pos;	
			$data["outcomes"][1]["data"][$key] = (int) ($row->tests - $row->pos);	
			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($row->pos, $row->tests);
		}
		return view('charts.dual_axis', $data);
	}


	public function starting_point()
	{
		$date_query = Lookup::date_query();

		$rows = DB::table('m_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_pmtct.facility')
			->selectRaw("SUM(on_haart_anc) AS on_haart_anc, SUM(start_art_anc) AS anc, SUM(start_art_lnd) AS lnd, SUM(start_art_pnc) AS pnc, SUM(start_art_pnc_6m) AS pnc_6m")
			->when(true, $this->get_callback('anc'))
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);
		// $data['stacking_false'] = true;

		$data['outcomes'][0]['name'] = "Started at PNC 6w-6m (*)";
		$data['outcomes'][1]['name'] = "Started at PNC < 6w (*)";
		$data['outcomes'][2]['name'] = "Started at L&D (*)";
		$data['outcomes'][3]['name'] = "Started at ANC";
		$data['outcomes'][4]['name'] = "ON HAART (1st ANC) (*)";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";
		$data['outcomes'][4]['type'] = "column";

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->pnc_6m;
			$data["outcomes"][1]["data"][$key] = (int) $row->pnc;
			$data["outcomes"][2]["data"][$key] = (int) $row->lnd;
			$data["outcomes"][3]["data"][$key] = (int) $row->anc;
			$data["outcomes"][4]["data"][$key] = (int) $row->on_haart_anc;
		}
		return view('charts.bar_graph', $data);
	}

	public function discovery_positivity()
	{
		$date_query = Lookup::date_query();

		$rows = DB::table('m_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_pmtct.facility')
			->selectRaw("SUM(known_pos_anc) AS known_pos_anc, SUM(positives_anc) AS anc, SUM(positives_lnd) AS lnd, SUM(positives_pnc) AS pnc, SUM(positives_pnc6m) AS pnc_6m")
			->when(true, $this->get_callback('anc'))
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Positive Result at PNC 6w-6m (*)";
		$data['outcomes'][1]['name'] = "Positive Result at PNC < 6w (*)";
		$data['outcomes'][2]['name'] = "Positive Result at L&D";
		$data['outcomes'][3]['name'] = "Positive Result at ANC";
		$data['outcomes'][4]['name'] = "Known Positive (1st ANC)";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";
		$data['outcomes'][4]['type'] = "column";

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->pnc_6m;
			$data["outcomes"][1]["data"][$key] = (int) $row->pnc;
			$data["outcomes"][2]["data"][$key] = (int) $row->lnd;
			$data["outcomes"][3]["data"][$key] = (int) $row->anc;
			$data["outcomes"][4]["data"][$key] = (int) $row->known_pos_anc;
		}
		return view('charts.bar_graph', $data);
	}


	public function male_testing()
	{
		$date_query = Lookup::date_query();

		$rows = DB::table('m_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_pmtct.facility')
			->selectRaw("SUM(known_status_before_male) AS known_status_before_male, SUM(initial_male_test_anc) AS anc, SUM(initial_male_test_lnd) AS lnd, SUM(initial_male_test_pnc) AS pnc")
			->when(true, $this->get_callback('anc'))
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "Males Tested PNC (*)";
		$data['outcomes'][1]['name'] = "Males Tested L&D";
		$data['outcomes'][2]['name'] = "Males Tested ANC";
		$data['outcomes'][3]['name'] = "Known Status (1st ANC) (*)";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->pnc;
			$data["outcomes"][1]["data"][$key] = (int) $row->lnd;
			$data["outcomes"][2]["data"][$key] = (int) $row->anc;
			$data["outcomes"][3]["data"][$key] = (int) $row->known_status_before_male;
		}
		return view('charts.bar_graph', $data);
	}

	public function eid()
	{
		$date_query = Lookup::date_query();

		$rows = DB::table('m_pmtct')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_pmtct.facility')
			->selectRaw("SUM(initial_pcr_2m) AS initial_pcr_2m, SUM(initial_pcr_12m) AS initial_pcr_12m")
			->when(true, $this->get_callback('initial_pcr_2m'))
			->whereRaw($date_query)
			->get();

		$date_query = Lookup::apidb_date_query();
		$api_rows = DB::table("apidb.site_summary")
			->join('hcm.view_facilitys', 'view_facilitys.id', '=', 'site_summary.facility')
			->selectRaw("SUM(`infantsless2m`) as `l2m`, SUM(`infantsabove2m`) as `g2m` ")
			->when(true, $this->get_callback('l2m'))
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);
		
		$data['outcomes'][0]['name'] = "> 2 months (DHIS)";
		$data['outcomes'][1]['name'] = "< 2 months (DHIS)";
		$data['outcomes'][2]['name'] = "> 2 months (NASCOP)";
		$data['outcomes'][3]['name'] = "< 2 months (NASCOP)";
		// $data['outcomes'][2]['name'] = "< 2 months Contribution";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";

		$data['outcomes'][0]['stack'] = 'dhis';
		$data['outcomes'][1]['stack'] = 'dhis';
		$data['outcomes'][2]['stack'] = 'apidb';
		$data['outcomes'][3]['stack'] = 'apidb';

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->initial_pcr_2m;
			$data["outcomes"][1]["data"][$key] = (int) $row->initial_pcr_12m;

			$nascop = Lookup::get_val($row, $api_rows, ['l2m', 'g2m']);

			$data["outcomes"][2]["data"][$key] = (int) $nascop['l2m'];
			$data["outcomes"][3]["data"][$key] = (int) $nascop['g2m'];
		}
		return view('charts.bar_graph', $data);		
	}
}
