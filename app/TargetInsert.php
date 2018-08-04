<?php

namespace App;

use Excel;
use DB;
use App\Facility;

class TargetInsert
{
    

	public static function current()
	{
		ini_set("memory_limit", "-1");
		$path = public_path('targets/new_2018.xlsx');
		$data = Excel::load($path, function($reader){

		})->get(['mfl', 'new']);
		foreach ($data as $row) {
			print_r($row);
			break;
		}
		// dd($data);
	}
    

	public static function current_2018()
	{
		ini_set("memory_limit", "-1");
		$path = public_path('targets/current_2018.xlsx');
		$data = Excel::load($path, function($reader){

		})->get(['mfl', 'current']);

		$unknown = [];

		foreach ($data as $row) {
			$facility = Facility::where('facilitycode', $row->mfl)->first();
			if(!$facility){
				$unknown[] = $row->mfl;
				continue;
			}
			DB::table('t_hiv_and_tb_treatment')
					->where(['facility' => $facility->id, 'financial_year' => 2018])
					->update(['on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038' => $row->current]);

		}
		print_r($unknown);
	}
    

	public static function new_2018()
	{
		ini_set("memory_limit", "-1");
		$path = public_path('targets/new_2018.xlsx');
		$data = Excel::load($path, function($reader){

		})->get(['mfl', 'new']);

		foreach ($data as $row) {
			$facility = Facility::where('facilitycode', $row->mfl)->first();
			DB::table('t_hiv_and_tb_treatment')
					->where(['facility' => $facility->id, 'financial_year' => 2018])
					->update(['start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026' => $row->new]);

		}
	}


}
