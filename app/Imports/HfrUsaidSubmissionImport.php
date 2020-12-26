<?php

namespace App\Imports;

use App\Facility;
use App\Week;
use App\HfrSubmission;
use App\Lookup;
use DB;
use Carbon\Carbon;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HfrUsaidSubmissionImport implements OnEachRow, WithHeadingRow
{

    private $data_columns;
    private $table_name;

    function __construct()
    {
    	$this->table_name = 'd_hfr_submission';

		$columns = HfrSubmission::columns();

		foreach ($columns as $key => $column) {
			$this->data_columns[$column['usaid_name']] = $column['column_name'];
		}

		session(['updated_rows' => [], 'problem_rows' => []]);
    }

    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray(null, true)));
    	// dd($row);
    	// if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

		$fac = Facility::where('facility_uid', $row->orgunituid)->first();
		if(!$fac) return;

		$week = Week::where(['start_date' => Carbon::createFromFormat('m/d/Y', $row->date) ])->first();
		if(!$week) return;

		$update_data = ['dateupdated' => date("Y-m-d")];

		foreach ($row as $key => $value) {
			if(isset($this->data_columns[$key])){
				$update_data[$this->data_columns[$key]] = (int) $value;
			}
		}

		// dd($update_data);

		if(env('APP_ENV') != 'testing') {
			$updated = DB::table($this->table_name)
			->where(['facility' => $fac->id, 'week_id' => $week->id, ])
			->update($update_data);
		}
    }

}
