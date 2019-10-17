<?php

namespace App\Imports;

use App\Facility;
use App\Week;
use App\SurgeColumn;
use DB;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SurgeImport implements OnEachRow, WithHeadingRow
{

    private $surge_columns;

    function __construct()
    {
		$surge_columns = SurgeColumn::all();

		$columns = [];

		foreach ($surge_columns as $key => $value) {
			$columns[$value->excel_name] = $value->column_name;
		}

        $this->surge_columns = $columns;
    }

    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray()));
    	if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

		$fac = Facility::where('facilitycode', $row->mfl_code)->first();
		if(!$fac) return;

		$week = Week::where(['financial_year' => $row->financial_year, 'week_number' => $row->week_number])->first();

		$update_data = ['dateupdated' => date("Y-m-d")];

		foreach ($row as $key => $value) {
			if(isset($this->surge_columns[$key])){
				$update_data[$this->surge_columns[$key]] = (int) $value;
			}
		}

		if(env('APP_ENV') != 'testing') {
			DB::connection('mysql_wr')->table('d_surge')
			->where(['facility' => $fac->id, 'week_id' => $week->id])
			->update($update_data);
		}
    }

}
