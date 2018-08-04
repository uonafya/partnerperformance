<?php

namespace App;

use Excel;

class TargetInsert
{
    

	public function current()
	{
		ini_set("memory_limit", "-1");
		$path = public_path('targets/new_2018.xlsx');
		$data = Excel::load($path, function($reader){

		})->get();
		foreach ($data as $row) {
			print_r($row);
			break;
		}
		// dd($data);
	}
}
