<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class CircumcisionController extends Controller
{

	public function summary()
	{		
		$date_query = Lookup::date_query();
		$data = Lookup::table_data();

		$sql = "SUM(circumcised_below1) AS below1, SUM(circumcised_below10) AS below10, 
			SUM(circumcised_below15) AS below15, SUM(circumcised_below20) AS below20, 
			SUM(circumcised_below25) AS below25, SUM(circumcised_above25) AS above25, 
			SUM(circumcised_total) AS total";

		$data['rows'] = DB::table('m_circumcision')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_circumcision.facility')
			->selectRaw($sql)
			->when(true, $this->get_callback('circumcised_total'))
			->whereRaw($date_query)
			->get();

		return view('tables.circumcision_summary', $data);
	}
}
