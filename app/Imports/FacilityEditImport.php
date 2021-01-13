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

class FacilityEditImport implements OnEachRow, WithHeadingRow
{
    private $data_columns;
    private $table_name;

    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray(null, true)));
    	// dd($row);
        $mfl_code = preg_replace("/[^<0-9.]/", "", $row->mfl_code);
    	if(!is_numeric($mfl_code) || (is_numeric($mfl_code) && $mfl_code < 10000)) return;

		$fac = Facility::where('facilitycode', $mfl_code)->first();
		if(!$fac) return;

		$fac->facility_uid = $row->facility_uid ?? $row->facility_or_community_uid;
		$fac->save();
    }

}
