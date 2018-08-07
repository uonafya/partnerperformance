<?php

namespace App;

use Excel;
use DB;
use App\Facility;

class TargetInsert
{

	public static function insert($year)
	{
		if($year == 2018){
			self::current_2018();
			self::new_2018();
			self::tests_2018();
		}
		else if($year == 2019){
			self::current_new_2019();
			self::tests_2019();
			self::pmtct_stat_2019();
			self::pmtct_art_2019();
		}
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
			DB::connection('mysql_wr')->table('t_hiv_and_tb_treatment')
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

		$unknown = [];

		foreach ($data as $row) {
			$facility = Facility::where('facilitycode', $row->mfl)->first();
			if(!$facility){
				$unknown[] = $row->mfl;
				continue;
			}
			DB::connection('mysql_wr')->table('t_hiv_and_tb_treatment')
					->where(['facility' => $facility->id, 'financial_year' => 2018])
					->update(['start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026' => $row->new]);

		}
		print_r($unknown);
	}

	public static function tests_2018()
	{
		ini_set("memory_limit", "-1");
		$path = public_path('targets/tests_2018.xlsx');
		$data = Excel::load($path, function($reader){

		})->get(['mfl', 'tests', 'pos']);

		$unknown = [];

		foreach ($data as $row) {
			$facility = Facility::where('facilitycode', $row->mfl)->first();
			if(!$facility){
				$unknown[] = $row->mfl;
				continue;
			}
			DB::connection('mysql_wr')->table('t_hiv_testing_and_prevention_services')
					->where(['facility' => $facility->id, 'financial_year' => 2018])
					->update([
						'positive_total_(sum_hv01-18_to_hv01-27)_hv01-26' => $row->pos,
						'tested_total_(sum_hv01-01_to_hv01-10)_hv01-10' => $row->tests,
					]);

		}
		print_r($unknown);		
	}
    

	public static function current_new_2019()
	{
		ini_set("memory_limit", "-1");
		$path = public_path('targets/new_current_2019.xlsx');
		$data = Excel::load($path, function($reader){

		})->get(['mfl', 'new', 'current']);

		$unknown = [];

		foreach ($data as $row) {
			$facility = Facility::where('facilitycode', $row->mfl)->first();
			if(!$facility){
				$unknown[] = $row->mfl;
				continue;
			}
			$facility = Facility::where('facilitycode', $row->mfl)->first();
			DB::connection('mysql_wr')->table('t_hiv_and_tb_treatment')
					->where(['facility' => $facility->id, 'financial_year' => 2019])
					->update([
						'start_art_total_(sum_hv03-018_to_hv03-029)_hv03-026' => $row->new,
						'on_art_total_(sum_hv03-034_to_hv03-043)_hv03-038' => $row->current,
					]);

		}
		print_r($unknown);
	}

	public static function tests_2019()
	{
		ini_set("memory_limit", "-1");
		$path = public_path('targets/tests_2019.xlsx');
		$data = Excel::load($path, function($reader){

		})->get(['mfl', 'tests', 'pos']);

		$unknown = [];

		foreach ($data as $row) {
			$facility = Facility::where('facilitycode', $row->mfl)->first();
			if(!$facility){
				$unknown[] = $row->mfl;
				continue;
			}
			DB::connection('mysql_wr')->table('t_hiv_testing_and_prevention_services')
					->where(['facility' => $facility->id, 'financial_year' => 2019])
					->update([
						'positive_total_(sum_hv01-18_to_hv01-27)_hv01-26' => $row->pos,
						'tested_total_(sum_hv01-01_to_hv01-10)_hv01-10' => $row->tests,
					]);

		}
		print_r($unknown);		
	}

	public static function pmtct_stat_2019()
	{
		ini_set("memory_limit", "-1");
		$path = public_path('targets/pmtct_stat_2019.xlsx');
		$data = Excel::load($path, function($reader){

		})->get(['mfl', 'pmtct_stat']);

		$unknown = [];

		foreach ($data as $row) {
			$facility = Facility::where('facilitycode', $row->mfl)->first();
			if(!$facility){
				$unknown[] = $row->mfl;
				continue;
			}
			DB::connection('mysql_wr')->table('t_prevention_of_mother-to-child_transmission')
					->where(['facility' => $facility->id, 'financial_year' => 2019])
					->update(['known_hiv_status_total_hv02-07' => $row->pmtct_stat]);

		}
		print_r($unknown);		
	}

	public static function pmtct_art_2019()
	{
		ini_set("memory_limit", "-1");
		$path = public_path('targets/pmtct_art_2019.xlsx');
		$data = Excel::load($path, function($reader){

		})->get(['mfl', 'pmtct_art']);

		$unknown = [];

		foreach ($data as $row) {
			$facility = Facility::where('facilitycode', $row->mfl)->first();
			if(!$facility){
				$unknown[] = $row->mfl;
				continue;
			}
			DB::connection('mysql_wr')->table('t_prevention_of_mother-to-child_transmission')
					->where(['facility' => $facility->id, 'financial_year' => 2019])
					->update(['on_maternal_haart_total_hv02-20' => $row->pmtct_art]);

		}
		print_r($unknown);		
	}



}
