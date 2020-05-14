<?php

namespace App\Imports;

use App\Facility;
use DB;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TargetsImport implements OnEachRow, WithHeadingRow
{

    private $table_name;
    private $financial_year;

    function __construct()
    {
    	$this->table_name = 't_facility_target';
    	$financial_year = date('Y');
		if(date('m') > 9) $financial_year++;
		$this->financial_year = $financial_year;
    }


    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray()));

    	if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

		$fac = Facility::where('facilitycode', $row->mfl_code)->first();
		if(!$fac) return;

		if(env('APP_ENV') != 'testing') {
			DB::connection('mysql_wr')->table($this->table_name)
			->where(['facility' => $fac->id, 'financial_year' => $this->financial_year ])
			->update(['gbv' => $row->gbv]);
		}
	}
}
