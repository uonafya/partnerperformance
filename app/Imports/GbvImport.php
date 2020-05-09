<?php

namespace App\Imports;

use App\Facility;
use App\Week;
use App\SurgeColumn;
use App\SurgeModality;
use DB;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GbvImport implements OnEachRow, WithHeadingRow
{

    private $gbv_columns;

    function __construct()
    {
    	$modalities = SurgeModality::where(['tbl_name' => 'd_gender_based_violence'])->get()->pluck('id')->toArray();
		$gbv_columns = SurgeColumn::whereIn('modality_id', $modalities)->get();

		$columns = [];

		foreach ($gbv_columns as $key => $value) {
			$columns[$value->excel_name] = $value->column_name;
		}

        $this->gbv_columns = $columns;
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
			if(isset($this->gbv_columns[$key])){
				$update_data[$this->gbv_columns[$key]] = (int) $value;
			}
		}

		if(env('APP_ENV') != 'testing') {
			DB::connection('mysql_wr')->table('d_gender_based_violence')
			->where(['facility' => $fac->id, 'week_id' => $week->id])
			->update($update_data);
		}
    }

}
