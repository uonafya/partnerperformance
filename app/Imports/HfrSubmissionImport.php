<?php

namespace App\Imports;

use App\Facility;
use App\Period;
use App\HfrSubmission;
use App\Lookup;
use DB;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HfrSubmissionImport implements OnEachRow, WithHeadingRow
{

    private $data_columns;
    private $table_name;

    function __construct()
    {
    	$this->table_name = 'd_hfr_submission';

		$columns = HfrSubmission::columns();

		foreach ($columns as $key => $column) {
			$this->data_columns[$column['alias_name']] = $column['column_name'];
		}

		session(['updated_rows' => [], 'problem_rows' => []]);
    }

    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray(null, true)));
    	dd($row);
    	if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

    	$updated_rows = session('updated_rows');
    	$problem_rows = session('problem_rows');

		$fac = Facility::where('facilitycode', $row->mfl_code)->first();
		if(!$fac) return;

		$month = (int) $row->month;

		$period = Period::where(['financial_year' => $row->financial_year, 'month' => $month])->first();
		if(!$period) return;

		$update_data = ['dateupdated' => date("Y-m-d")];

		foreach ($row as $key => $value) {
			if(isset($this->data_columns[$key])){
				$update_data[$this->data_columns[$key]] = (int) $value;
			}
		}

		if(env('APP_ENV') != 'testing') {
			$updated = DB::table($this->table_name)
			->where(['facility' => $fac->id, 'period_id' => $period->id, ])
			->update($update_data);
		}
    }

}
