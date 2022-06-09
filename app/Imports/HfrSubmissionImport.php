<?php

namespace App\Imports;

use App\Facility;
use App\Week;
use App\HfrSubmission;
use App\Lookup;
use App\TempHfrSubmission;
use DB;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Row;
// use Maatwebsite\Excel\Concerns\OnEachRow;

// class HfrSubmissionImport implements OnEachRow, WithHeadingRow
class HfrSubmissionImport implements ToCollection, WithHeadingRow
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

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    	// Get all the facilities in the upload file
    	$facilities = Facility::whereIn('facilitycode', $collection->pluck('mfl_code')->toArray())->get();

    	// Get all the weeks of the financial years in the upload file
    	$weeks = Week::whereIn('financial_year', $collection->pluck('financial_year')->toArray())->get();

    	/**/
    	$upload_data = $this->buildTempInsert($collection, $facilities, $weeks);
    	$uploaded = TempHfrSubmission::insert($upload_data);
    	TempHfrSubmission::updateHfrSubmissionsFromTemp();
    	/**/
    }


    /**
     * 
     * @param \Illuminate\Support\Collection $collection
     * @param \Illuminate\Support\Collection (\App\Facility)$facility
     * @param \Illuminate\Support\Collection (\App\Week)$weeks
     */

    private function buildTempInsert($collection, $facilities, $weeks)
    {
    	$upload_data = [];
		$temphfrsubmissions = TempHfrSubmission::get();
    	foreach($collection as $item) {
    		if (!is_numeric($item['mfl_code']) || (is_numeric($item['mfl_code']) && $item['mfl_code'] < 10000)) continue;

	    	// Check if the mfl in the current iteration exists in the facility table
	    	$facility = $facilities->where('facilitycode', $item['mfl_code']);
			if ($facility->isEmpty())
				continue;
			$fac = $facility->first();

			// Check if the current week in iteration exists in the weeks table
			$week = $weeks->where('week_number', $item['week_number']);
			if ($week->isEmpty())
				continue;
			$week = $week->first();

			// Skip the facilities which have already submitted for the current week
			if (!$temphfrsubmissions->where('week_id', $week->id)->where('facility', $fac->id)->isEmpty())
				continue;

			$data = [
				'dateupdated' => date('Y-m-d'),
				'facility' => $fac->id,
				'week_id' => $week->id
			];
			foreach ($item as $key => $value) {
				$value = ($value == "") ? 'NULL' : $value;
				if(isset($this->data_columns[$key]))
					$data [$this->data_columns[$key]] = $value;
			}
			$upload_data[] = $data;
    	}
    	return $upload_data;
    }

    
    /*
    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray(null, true)));
    	
    	if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

		$fac = Facility::where('facilitycode', $row->mfl_code)->first();
		if(!$fac) return;

		$week = Week::where(['financial_year' => $row->financial_year, 'week_number' => $row->week_number])->first();
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
    */
}
