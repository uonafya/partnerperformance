<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;
use App\Facility;
use App\SurgeColumn;
use App\SurgeColumnView;
use App\SurgeModality;
use App\SurgeAge;
use App\AgeCategory;
use App\Week;

class WeeklyController extends Controller
{
	private $my_table = 'd_weeklies';

	// VMMC
	public function vmmc_summary()
	{
		$groupby = session('filter_groupby', 1);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->join('surge_columns_view', 'surge_columns_view.id', '=', "{$this->my_table}.column_id")
			->selectRaw("age_name, SUM(value) AS value ")
			->whereRaw(Lookup::surge_columns_query(false, false, true))
			->when(true, $this->get_callback())
			->when(($groupby < 10), function($query){
				return $query->orderBy('div_id', 'asc');
			})
			->orderBy('max_age', 'asc')
			->get();

		$data['div'] = str_random(15);
		$data['suffix'] = '';
		$data['yAxis'] = 'Number of Clients';

		$ages = SurgeAge::vmmc_circ()->get();

		$bars = $ages->pluck(['age_name'])->toArray();
		Lookup::bars($data, $bars);
		$data['categories'] = [];

		foreach ($rows as $row) {
			$needle = Lookup::get_category($row);
			$key = array_search($needle, $data['categories']);

			if(!is_int($key)) $data['categories'][] = $needle;
			$key = array_search($needle, $data['categories']);
			$item = array_search($row->age_name, $bars);

			$data["outcomes"][$item]["data"][$key] = (int) $row->value;
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

		$week_id = $request->input('week');
		$m_name = $request->input('modality');
		$age_category_id = $request->input('age_category_id');
		$gender_id = $request->input('gender_id');

		$week = Week::findOrFail($week_id);

		$sql = "countyname as County, Subcounty,
		financial_year AS `Financial Year`, year AS `Calendar Year`, week_number as `Week Number`, 
		facilitycode AS `MFL Code`, name AS `Facility`,
		alias_name AS `Column Name`, value AS `Value`";

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->join('surge_columns_view', "{$this->my_table}.column_id", '=', 'surge_columns_view.id')
			->selectRaw($sql)
			->where(['partner' => $partner->id, 'week_id' => $week_id, 'modality' => $m_name])
			->when($age_category_id, function($query) use ($age_category_id){
				return $query->where('age_category_id', $age_category_id);
			})
			->when($gender_id, function($query) use ($gender_id){
				return $query->where('gender_id', $gender_id);
			})
			->orderBy('view_facilities.name', 'asc')
			->orderBy('column_id', 'asc')
			->get();

		$filename = str_replace(' ', '_', $partner->name) . '_' . $m_name . '_for_' . $week->start_date . '_to_' . $week->end_date;

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

		$m_name = $request->input('modality');
		$m = SurgeModality::where(['modality' => $m_name])->first();
		$columns = SurgeColumn::where(['modality_id' => $m->id])->get();
		$weeks = Week::all();

		$today = date('Y-m-d');

		foreach ($data as $row_key => $row){
			$hasdata = false;

			$col = $columns->where('alias_name', $row->column_name)->first();
			$w = $weeks->where('financial_year', $row->financial_year)->where('week_number', $row->week_number)->first();
			$val = (int) $row->value;
			if(!$col || !$val || !$w) continue;

			if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) continue;
			$fac = Facility::where('facilitycode', $row->mfl_code)->first();

			if(!$fac) continue;
			$update_data = ['dateupdated' => $today, 'value' => $val]; 

			DB::table($this->my_table)->where(['facility' => $fac->id, 'week_id' => $w->id, 'column_id' => $c->id])->update($update_data);
		}
		session(['toast_message' => 'The updates have been made.']);
		return back();

	}
}
