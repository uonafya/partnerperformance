<?php

namespace App\Imports;

use DB;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TxCurrentImport implements OnEachRow, WithHeadingRow
{
	private $periods;
    private $surge_columns;

	public function __construct()
	{
		$m = \App\SurgeModality::where(['modality' => 'tx_curr'])->first();
		$this->surge_columns = \App\SurgeColumn::where(['modality_id' => $m->id])->get();
		$this->periods = \App\Period::where('year', '>', 2018)->get();
	}

    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray()));

		$col = $this->surge_columns->where('alias_name', $row->column_name)->first();
		$p = $this->periods->where('financial_year', $row->financial_year)->where('month', $row->month)->first();
		$val = (int) $row->value;
		if(!$col || !$val || !$p) return;

		if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;
		$fac = \App\Facility::where('facilitycode', $row->mfl_code)->first();

		if(!$fac) return;
		$update_data = ['dateupdated' => date('Y-m-d'), 'value' => $val]; 

		if(env('APP_ENV') != 'testing')  DB::table('d_tx_curr')->where(['facility' => $fac->id, 'period_id' => $p->id, 'column_id' => $c->id])->update($update_data);
    }
}
