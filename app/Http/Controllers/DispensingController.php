<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;
use App\Facility;

class DispensingController extends Controller
{

	private $my_table = 'd_dispensing';

	// Yield by modality
	public function modality_yield()
	{
		$age_category_id = session('filter_age_category_id');
		$gender_id = session('filter_gender');


		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback())
			->when($age_category_id, function($query) use ($age_category_id){
				return $query->where('age_category_id', $age_category_id);
			})
			->when($gender_id, function($query) use ($gender_id){
				return $query->where('gender_id', $gender_id);
			})
			->get();

		$data['div'] = str_random(15);

		$sets = [
			[
				'name' => 'Dispensed One',
			]
		];

		$data['outcomes'][0]['name'] = "Positive";


		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);
		}
		return view('charts.line_graph', $data);
	}

}
