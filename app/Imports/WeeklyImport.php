<?php

namespace App\Imports;

use App\Facility;
use App\Week;
use DB;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WeeklyImport implements OnEachRow, WithHeadingRow
{

    private $surge_columns;

    function __construct($m_name)
    {
		$m = \App\SurgeModality::where(['modality' => $m_name])->first();
		$surge_columns = \App\SurgeColumn::where(['modality_id' => $m->id])->get();
    }

    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row));
    	if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

		$fac = Facility::where('facilitycode', $row->mfl_code)->first();
		if(!$fac) return;	

		$col = $this->surge_columns->where('alias_name', $row->column_name)->first();
		$w = Week::where('financial_year', $row->financial_year)->where('week_number', $row->week_number)->first();
		$val = (int) $row->value;
		if(!$col || !$val || !$w) continue;

		$update_data = ['dateupdated' => $today, 'value' => $val]; 

		DB::table('d_weeklies')->where(['facility' => $fac->id, 'week_id' => $w->id, 'column_id' => $c->id])->update($update_data);
}