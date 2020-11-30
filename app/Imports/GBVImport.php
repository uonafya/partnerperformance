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

class GBVImport implements OnEachRow, WithHeadingRow
{

    private $gbv_columns;
    private $table_name;

    function __construct()
    {
    	$this->table_name = 'd_gender_based_violence';
    	$modalities = SurgeModality::where(['tbl_name' => $this->table_name])->get()->pluck('id')->toArray();
		$gbv_columns = SurgeColumn::whereIn('modality_id', $modalities)->get();

		$columns = [];

		foreach ($gbv_columns as $key => $value) {
			$columns[$value->excel_name] = $value->column_name;
		}

        $this->gbv_columns = $columns;
    }

    public function onRow(Row $row)
    {
    	// dd($row->toArray()['gbv_sexual_violence_unknown_male']);
    	$row = json_decode(json_encode($row->toArray(null, true)));
    	// dd($row);
    	if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

		$fac = Facility::where('facilitycode', $row->mfl_code)->first();
		if(!$fac) dd($row);
		// if(!$fac) return;

		$period = Period::where(['financial_year' => $row->financial_year, 'month' => $row->month])->first();
		// if(auth()->user()->id == 1) $period = Period::where(['year' => $row->calendar_year, 'month' => $row->month])->first();
		if(!$period) dd($row);
		// if(!$period) return;

		$update_data = ['dateupdated' => date("Y-m-d")];

		foreach ($row as $key => $value) {
			if(isset($this->gbv_columns[$key])){
				$update_data[$this->gbv_columns[$key]] = (int) $value;
			}
		}
		// dd($update_data);

		if(env('APP_ENV') != 'testing') {
			$Updated_rows = DB::table($this->table_name)
			->where(['facility' => $fac->id, 'period_id' => $period->id, ])
			->update($update_data);

			if(!$Updated_rows) dd($row);

		}
    }

}
