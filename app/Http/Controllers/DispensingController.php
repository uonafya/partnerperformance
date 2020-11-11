<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;
use App\Facility;
use App\SurgeGender;
use App\AgeCategory;
use App\Period;

class DispensingController extends Controller
{

	private $my_table = 'd_dispensing';

	public function summary()
	{
		$age_category_id = session('filter_age_category_id');
		$gender_id = session('filter_gender');

		$data['div'] = str_random(15);
		$data['suffix'] = '';
		$data['yAxis'] = 'Number of Clients';


		$t = ['Dispensed One', 'Dispensed Two', 'Dispensed Three', 'Dispensed Four', 'Dispensed Five', 'Dispensed Six', ];
		$props = [];

		$sql = '';
		foreach ($t as $key => $value) {
			$data['outcomes'][$key]['type'] = "column";
			$data['outcomes'][$key]['name'] = $value;
			$str = strtolower(str_replace(' ', '_', $value));
			$props[] = $str;
			$sql .= "SUM({$str}) AS {$str}, ";
		}

		$sql = substr($sql, 0, -2);

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

		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			foreach ($props as $prop_key => $prop) {
				$data["outcomes"][$prop_key]["data"][$key] = (int) $row->$prop;
			}
		}
		return view('charts.line_graph', $data);
	}


	public function download_excel(Request $request)
	{
		$partner = session('session_partner');
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}
		$data = [];

		$month = $request->input('month', date('m')-1);
		$financial_year = $request->input('financial_year', date('Y'));
		$age_category_id = $request->input('age_category_id');
		$gender_id = $request->input('gender_id');

		$t = ['Dispensed One', 'Dispensed Two', 'Dispensed Three', 'Dispensed Four', 'Dispensed Five', 'Dispensed Six', ];

		$sql = "countyname as County, Subcounty,
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name`,
		facilitycode AS `MFL Code`, name AS `Facility`, gender AS `Gender`, age_category AS `Age Category`";

		foreach ($t as $key => $value) {
			$str = strtolower(str_replace(' ', '_', $value));
			$sql .= ", {$str} AS `{$value}`";
		}

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->join('age_categories', "{$this->my_table}.age_category_id", '=', 'age_categories.id')
			->join('surge_genders', "{$this->my_table}.gender_id", '=', 'surge_genders.id')
			->selectRaw($sql)
			->where(['partner' => $partner->id, 'financial_year' => $financial_year, 'month' => $month])
			->when($age_category_id, function($query) use ($age_category_id){
				return $query->where('age_category_id', $age_category_id);
			})
			->when($gender_id, function($query) use ($gender_id){
				return $query->where('gender_id', $gender_id);
			})
			->orderBy('view_facilities.name', 'asc')
			->orderBy('age_category_id', 'asc')
			->orderBy('gender_id', 'asc')
			->get();

		$filename = str_replace(' ', '_', $partner->name) . '_FY_' . $financial_year . '_' . Lookup::resolve_month($month) . '_dispensing';


		foreach ($rows as $row) {
			$row_array = get_object_vars($row);
			$data[] = $row_array;
		}

    	$path = storage_path('exports/' . $filename . '.xlsx');
    	if(file_exists($path)) unlink($path);

    	Excel::create($filename, function($excel) use($data){
    		$excel->sheet('sheet1', function($sheet) use($data){
    			$sheet->fromArray($data);
    		});

    	})->store('xlsx');

    	return response()->download($path);
	}

	public function upload_excel(Request $request)
	{
		ini_set('memory_limit', '-1');
		if (!$request->hasFile('upload')){
	        session(['toast_error' => 1, 'toast_message' => 'Please select a file before clicking the submit button.']);
			return back();
		}
		$file = $request->upload->path();

		$data = Excel::load($file, function($reader){
			$reader->toArray();
		})->get();

		$partner = session('session_partner');
		
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}

		// dd($data);

		$genders = SurgeGender::all();
		$age_categories = AgeCategory::all();
		$periods = Period::where('year', '>', 2018)->get();

		$t = ['Dispensed One', 'Dispensed Two', 'Dispensed Three', 'Dispensed Four', 'Dispensed Five', 'Dispensed Six', ];
		$props = [];

		foreach ($t as $key => $value) {
			$str = strtolower(str_replace(' ', '_', $value));
			$props[] = $str;
		}

		$today = date('Y-m-d');

		foreach ($data as $row_key => $row){
			$hasdata = false;
			$update_data['dateupdated'] = $today; 

			foreach ($props as $key => $prop) {
				$update_data[$prop] = (int) $row->$prop;
				if($update_data[$prop] > 0) $hasdata = true;
			}

			if(!$hasdata) continue;

			$g = $genders->where('gender', $row->gender)->first();
			$a = $genders->where('age_category', $row->age_category)->first();
			$p = $periods->where('financial_year', $row->financial_year)->where('month', $row->month)->first();

			if(!$a || !$g || !$p) continue;

			if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) continue;
			$fac = Facility::where('facilitycode', $row->mfl_code)->first();

			if(!$fac) continue;

			DB::table($this->my_table)->where(['facility' => $fac->id, 'period_id' => $p->id, 'age_category_id' => $a->id, 'gender_id' => $g->id])->update($update_data);
		}
		session(['toast_message' => 'The updates have been made.']);
		return back();
	}
}
