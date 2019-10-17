<?php

namespace App\Imports;

use DB;
use \App\Period;
use \App\Facility;
use \App\PNS;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PNSImport implements OnEachRow, WithHeadingRow
{
	private $pns_columns;

	public function __construct()
	{
		$pns = new PNS;
		$columns = [];

		foreach ($pns->item_array as $key => $value) {
			$str = str_replace(' ', '_', strtolower($value));
			foreach ($pns->ages_array as $key2 => $value2) {
				$column_name = $key . '_' . $key2;
				$key_name = $str . '_' . str_replace(' ', '_', strtolower($value2));
				$key_name = str_replace('-', '_', $key_name);
				$columns[$key_name] = $column_name;
			}
		}
		$this->pns_columns = $columns;
	}


    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray()));
    	if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

		$fac = Facility::where('facilitycode', $row->mfl_code)->first();
		if(!$fac) return;

		$update_data = ['dateupdated' => date("Y-m-d")];
		$hasdata = false;

		foreach ($row as $key => $value) {
			if(isset($this->pns_columns[$key])){
				$update_data[$this->pns_columns[$key]] = (int) $value;
				if(((int) $value) > 0) $hasdata = true;
			}
		}

		if($hasdata && !$fac->is_pns){
			$fac->is_pns = 1;
			$fac->save();
		}

		$period = Period::where(['financial_year' => $row->financial_year, 'month' => $row->month])->first();
		if(!$period) return;

		if(env('APP_ENV') != 'testing'){
			DB::connection('mysql_wr')->table('d_pns')
			->where(['facility' => $fac->id, 'period_id' => $period->id, ])
			->update($update_data);
		}

    }
}
