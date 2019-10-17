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
    private $table_name;

    function __construct($m_name)
    {
		$m = \App\SurgeModality::where(['modality' => $m_name])->first();
		$this->surge_columns = \App\SurgeColumn::where(['modality_id' => $m->id])->get();
		$this->table_name = $m->tbl_name;
    }

    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray()));
    	if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;

		$fac = Facility::where('facilitycode', $row->mfl_code)->first();
		if(!$fac) return;	

		$col = $this->surge_columns->where('alias_name', $row->column_name)->first();
		$w = Week::where('financial_year', $row->financial_year)->where('week_number', $row->week_number)->first();
		$val = (int) $row->value;
		if(!$col || !$val || !$w) return;

		$update_data = ['dateupdated' => $today, 'value' => $val]; 

		if(env('APP_ENV') != 'testing')  DB::table($this->table_name)->where(['facility' => $fac->id, 'week_id' => $w->id, 'column_id' => $c->id])->update($update_data);
	}
}
