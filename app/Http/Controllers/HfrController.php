<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;
use App\HfrSubmission;

class HfrController extends Controller
{
	private $my_table = 'd_hfr_submission';


    public function get_hfr_sum($columns, $name)
    {
        $sql = "(";

        foreach ($columns as $column) {
            $sql .= "SUM(`{$column['column_name']}`) + ";
        }
        $sql = substr($sql, 0, -3);
        $sql .= ") AS {$name} ";
        return $sql;
    }

	public function testing()
	{
		$tests = HfrSubmission::columns(true, 'hts_tst'); 
		$pos = HfrSubmission::columns(true, 'hts_tst_pos');
		$sql = $this->get_hfr_sum($tests, 'tests') . ', ' . $this->get_hfr_sum($pos, 'pos');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('tests'))
			->get();

		$data['div'] = str_random(15);
		$data['yAxis'] = "Total Number Tested";
		$data['yAxis2'] = "Yield (%)";
		$data['data_labels'] = true;
		$data['no_column_label'] = true;

		Lookup::bars($data, ["Positive", "Negative", "Yield"], "column");
		Lookup::splines($data, [2]);
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');
		Lookup::yAxis($data, 0, 1);

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->pos;
			$data["outcomes"][1]["data"][$key] = (int) ($row->tests - $row->pos);
			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($row->pos, $row->tests);
		}	
		return view('charts.dual_axis', $data);
	}

	public function linkage()
	{
		$pos = HfrSubmission::columns(true, 'hts_tst_pos');
		$tx_new = HfrSubmission::columns(true, 'tx_new');
		$sql = $this->get_hfr_sum($pos, 'pos') . ', ' . $this->get_hfr_sum($tx_new, 'tx_new');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('pos'))
			->get();

		$data['div'] = str_random(15);
		$data['yAxis'] = '';
		$data['yAxis2'] = "Linkage (%)";
		$data['data_labels'] = true;
		$data['no_column_label'] = true;

		Lookup::bars($data, ["TX New", "Not Linked", "Linkage"], "column");
		Lookup::splines($data, [2]);
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');
		Lookup::yAxis($data, 0, 1);


		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->tx_new;
			$data["outcomes"][1]["data"][$key] = (int) ($row->pos - $row->tx_new);
			$data["outcomes"][2]["data"][$key] = Lookup::get_percentage($row->tx_new, $row->pos);
			if($data["outcomes"][1]["data"][$key] < 0) {
				$data["outcomes"][1]["data"][$key] = (int) $row->tx_new;
				$data["outcomes"][2]["data"][$key] = 0;
			}
		}	
		return view('charts.dual_axis', $data);
	}

	public function tx_curr()
	{
		$tx_curr = HfrSubmission::columns(true, 'tx_curr');
		$sql = $this->get_hfr_sum($tx_curr, 'tx_curr');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('tx_curr'))
			->get();

		$data['div'] = str_random(15);

		Lookup::bars($data, ["TX Curr"], "column");

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->tx_curr;
		}	
		return view('charts.line_graph', $data);
	}

	public function prep_new()
	{
		$prep_new = HfrSubmission::columns(true, 'prep_new');
		$sql = $this->get_hfr_sum($prep_new, 'prep_new');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('prep_new'))
			->get();

		$data['div'] = str_random(15);

		Lookup::bars($data, ["PrEP New"], "column");

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->prep_new;
		}	
		return view('charts.line_graph', $data);
	}

	public function vmmc_circ()
	{
		$vmmc_circ = HfrSubmission::columns(true, 'vmmc_circ');
		$sql = $this->get_hfr_sum($vmmc_circ, 'vmmc_circ');

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('vmmc_circ'))
			->get();

		$data['div'] = str_random(15);

		Lookup::bars($data, ["VMMC Circ"], "column");

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->vmmc_circ;
		}	
		return view('charts.line_graph', $data);
	}

	public function tx_mmd()
	{
		$less_3m = HfrSubmission::columns(true, 'less_3m');
		$less_5m = HfrSubmission::columns(true, '3_5m');
		$above_6m = HfrSubmission::columns(true, 'above_6m');
		$sql = $this->get_hfr_sum($less_3m, 'less_3m') . ', ' . $this->get_hfr_sum($less_5m, 'less_5m') . ', ' . $this->get_hfr_sum($above_6m, 'above_6m');

    	$divisions_query = Lookup::divisions_query();
        $date_query = Lookup::date_query();

		$row = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->selectRaw($sql)
			->whereRaw($divisions_query)
            ->whereRaw($date_query)
			->first();

		$data['div'] = str_random(15);
		$data['yAxis'] = '';
		$data['data_labels'] = true;
		$data['no_column_label'] = true;

		Lookup::bars($data, ["TX MMD", '% of TX_CURR'], "column");
		Lookup::splines($data, [1]);
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' %');
		Lookup::yAxis($data, 0, 0);


		$data['categories'][0] = 'TX Curr &lt;3 months of ARVs dispensed';
		$data['categories'][1] = 'TX Curr 3-5 months of ARVs dispensed';
		$data['categories'][2] = 'TX Curr 6+ months of ARVs dispensed';

		$data["outcomes"][0]["data"][0] = (int) $row->less_3m;
		$data["outcomes"][0]["data"][1] = (int) $row->less_5m;
		$data["outcomes"][0]["data"][2] = (int) $row->above_6m;

		$total = $row->less_3m + $row->less_5m + $row->above_6m; Lookup::get_percentage($row->tx_new, $row->pos)

		$data["outcomes"][1]["data"][0] = Lookup::get_percentage($row->less_3m, $total);
		$data["outcomes"][1]["data"][1] = Lookup::get_percentage($row->less_5m, $total);
		$data["outcomes"][1]["data"][2] = Lookup::get_percentage($row->above_6m, $total);
		
		return view('charts.dual_axis', $data);
	}


}
