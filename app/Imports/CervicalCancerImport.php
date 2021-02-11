<?php

namespace App\Imports;

use App\Facility;
use App\Period;
use App\SurgeColumn;
use App\SurgeModality;
use DB;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CervicalCancerImport implements OnEachRow, WithHeadingRow
{

    private $cancer_columns;
    private $table_name;

    function __construct()
    {
    	$this->table_name = 'd_cervical_cancer';
    	$modalities = SurgeModality::where(['tbl_name' => $this->table_name])->get()->pluck('id')->toArray();
		$cancer_columns = SurgeColumn::whereIn('modality_id', $modalities)->get();

		$columns = [];

		foreach ($cancer_columns as $key => $value) {
			$columns[$value->excel_name] = $value->column_name;
		}

		session(['updated_rows' => [], 'problem_rows' => []]);

        $this->cancer_columns = $columns;
    }

    public function onRow(Row $row)
    {
    	// dd($row->toArray()['gbv_sexual_violence_unknown_male']);
    	$row = json_decode(json_encode($row->toArray(null, true)));
    	// dd($row);
    	if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

    	$updated_rows = session('updated_rows');
    	$problem_rows = session('problem_rows');

		$fac = Facility::where('facilitycode', $row->mfl_code)->first();
		/*if(!$fac){
			$row->error = 'Facility Not found';
			dd($row);
		}*/
		if(!$fac) return;

		$month = (int) $row->month;

		$period = Period::where(['financial_year' => $row->financial_year, 'month' => $month])->first();
		if(!$period) return;

		$update_data = ['dateupdated' => date("Y-m-d")];

		foreach ($row as $key => $value) {
			if(isset($this->cancer_columns[$key])){
				$update_data[$this->cancer_columns[$key]] = (int) $value;
			}
		}

		
		$db_row = DB::table($this->table_name)->where(['facility' => $fac->id, 'period_id' => $period->id])->first();
		if(!in_array($db_row->id, $updated_rows)){
			$updated_rows[] = $db_row->id;
			session(['updated_rows' => $updated_rows]);
		}else{
			$problem_rows[] = get_object_vars($row);
	    	session(['problem_rows' => $problem_rows]);
	    	return;
		}




		if(env('APP_ENV') != 'testing') {
			$updated = DB::table($this->table_name)
			->where(['facility' => $fac->id, 'period_id' => $period->id, ])
			->update($update_data);

			/*if(!$updated){
				$row->error = "No row updated";
				// $row->update_data = $update_data;
				$row->period = $period->id;
				$row->fac = $fac->id;
				$row->facility = $fac->name;
				$problem_rows[] = get_object_vars($row);
		    	session(['problem_rows' => $problem_rows]);
				// dd($row);
			}*/

		}
    }

}
