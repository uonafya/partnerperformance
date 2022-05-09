<?php

namespace App\Imports;

use App\Facility;
use App\Week;
use App\HfrSubmission;
use App\Lookup;
use DB;
use Carbon\Carbon;
use Str;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
// use Maatwebsite\Excel\Concerns\RemembersRowNumber;

class HfrUsaidSubmissionImport implements OnEachRow, WithHeadingRow
{

	// use RemembersRowNumber;

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
    	$original_row = $row->toArray();
    	$row = json_decode(json_encode($row->toArray(null, true)));
    	// dd($row);
    	// if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

    	$fac = null;

		
		if($row->orgunituid) $fac = Facility::where('facility_uid', $row->orgunituid)->first();
		if(!$fac) $fac = Facility::where('name','like', $row->orgunit)->first();
		
		// if(!$fac) return;
		if(!$fac){
			$facilities = session('missing_facilities');
			$facilities[] = ['Facility UID' => $row->orgunituid, 'Facility Name' => $row->orgunit];
			session(['missing_facilities' => $facilities]);
			return;
			// dd("facility " . $row->orgunit . ' uid ' . $row->orgunituid . ' not found');
		}

		$date_format = 'm/d/Y';
		if(strlen($row->date) < 9) $date_format = 'm/d/y';
		if(Str::startsWith($row->date, '2020/')) $date_format = 'Y/m/d';
		if(Str::endsWith($row->date, '2022')) $date_format = 'm/d/Y';

		if(Str::contains($row->date, ['October'])) $date_format = 'F d, Y';
		if(Str::contains($row->date, ['Nov', 'Dec'])) $date_format = 'd-M-y';
		if(Str::contains($row->date, ['January'])) $date_format = 'F d, Y';
		if(Str::contains($row->date, ['January'])) $date_format = 'F d, Y';
		

		$week = Week::where(['start_date' => Carbon::createFromFormat($date_format, $row->date)->toDateString()])->first();
		if(!$week) dd("Week starting on " . $row->date . " facility " . $row->orgunit . ' uid ' . $row->orgunituid . ' not found');

		$update_data = ['dateupdated' => date("Y-m-d")];

		/*foreach ($row as $key => $value) {
			if(isset($this->data_columns[$key])){
				$update_data[$this->data_columns[$key]] = (int) $value;
			}
		}*/ 

		$missing_columns = [];
		foreach ($this->data_columns as $excel_column => $db_column) {
			// if(!isset($row->$excel_column)) dd('Excel column ' . $excel_column . ' is missing');
			if(!isset($row->$excel_column)) $missing_columns[] = [$excel_column => $db_column];
			$update_data[$db_column] = (int) $row->$excel_column;
		}

		// if($missing_columns) dd($missing_columns);

		/*$update_data['week'] = $week;
		$update_data['facility'] = $fac;

		dd($update_data);*/

		$updated_row = DB::table($this->table_name)->where(['facility' => $fac->id, 'week_id' => $week->id, ])->first();
		if(!$updated_row) dd("Row is not in the DB.");

		if($updated_row->dateupdated){
			$duplicate_rows = session()->pull('duplicate_rows');

			$dup = ['Month' => date('M', strtotime($week->start_date)), 'Facility UID' => $fac->facility_uid, 'MFL Code' => $fac->facilitycode,	 'Week Start Date' => $week->start_date, ];

			foreach ($update_data as $key => $value) { $dup['original ' . $key] = $updated_row->$key; }

			foreach ($update_data as $key => $value) { $dup['duplicate ' . $key] = $value; }

			$duplicate_rows[] = $dup; session(['duplicate_rows' => $duplicate_rows]); return;
		}

		$original_row['db_row'] = $updated_row;

		$updated = DB::table($this->table_name)
		->where('id', $updated_row->id)
		// ->where(['facility' => $fac->id, 'week_id' => $week->id, ])
		->update($update_data);
		/*if(!$updated) {
			$original_row['reason'] = 'Updated is ' . $updated;
			$original_row['update_date'] = $update_data;
			dd($original_row);
		}*/

		// $row_number = session('row_number');
		// $row_number++;
		// session(['toast_message' => 'Row Number ' . $row_number, 'row_number' => $row_number]);
    }

    /*public function chunkSize(): int
    {
        return 50;
    }*/

}
