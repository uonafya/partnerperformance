<?php

namespace App\Imports;

use DB;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DispensingImport implements OnEachRow, WithHeadingRow
{
	private $props;
	private $genders;
	private $age_categories;
	private $periods;

	public function __construct()
	{
		$d = \App\Dispensing::$dispensations;
		$props = [];

		foreach ($d as $key => $value) {
			$str = strtolower(str_replace(' ', '_', $value));
			$props[] = $str;
		}
		$this->props = $props;
		$this->genders = \App\SurgeGender::all();
		$this->age_categories = \App\AgeCategory::all();
		$this->periods = \App\Period::where('year', '>', 2018)->get();
	}


    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray()));

		$hasdata = false;
		$update_data['dateupdated'] = date('Y-m-d'); 

		foreach ($this->props as $key => $prop) {
			$update_data[$prop] = (int) $row->$prop;
			if($update_data[$prop] > 0) $hasdata = true;
		}

		if(!$hasdata) return;

		$g = $this->genders->where('gender', $row->gender)->first();
		$a = $this->age_categories->where('age_category', $row->age_category)->first();
		$p = $this->periods->where('financial_year', $row->financial_year)->where('month', $row->month)->first();

		if(!$a || !$g || !$p) return;

		if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;
		$fac = \App\Facility::where('facilitycode', $row->mfl_code)->first();

		if(!$fac) return;

		if(env('APP_ENV') != 'testing') DB::table('d_dispensing')->where(['facility' => $fac->id, 'period_id' => $p->id, 'age_category_id' => $a->id, 'gender_id' => $g->id])->update($update_data);
    }
}
