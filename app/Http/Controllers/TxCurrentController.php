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
use App\Period;

class TxCurrentController extends Controller
{
	private $my_table = 'd_tx_curr';
	private $my_conditions = ['modality' => 'tx_curr'];


	public function gender()
	{
		$q = Lookup::groupby_query();
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$groupby = session('filter_groupby', 1);

		if($groupby != 12) $date_query = Lookup::year_month_query();
		else if($groupby > 9) return null;

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->join('surge_columns_view', 'surge_columns_view.id', '=', "{$this->my_table}.column_id")
			->selectRaw($q['select_query'] . ", gender, SUM(value) AS value ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::surge_columns_query(false, false, true))
			->groupby($q['group_array'][0], 'gender')
			->when(sizeof($q['group_array']) == 2, function($query) use($q){
				return $query->groupBy($q['group_array'][1]);
			})
			->when(true, function($query) use($groupby, $q) {
				if($groupby < 10) return $query->orderBy('div_id');
				return $query->orderBy($q['select_query']);
			})
			->orderBy('gender_id')
			->get();

		$data['div'] = str_random(15);
		$data['suffix'] = '';
		$data['yAxis'] = 'Number of Clients';

		Lookup::bars($data, ['Male', 'Female', 'Unknown']);

		$data['categories'] = [];

		foreach ($rows as $row) {
			$needle = Lookup::get_category($row);
			$key = array_search($needle, $data['categories']);

			if(!is_int($key)) $data['categories'][] = $needle;
			$key = array_search($needle, $data['categories']);

			if($row->gender == 'male') $item = 0;
			else if($row->gender == 'female') $item = 1;
			else{
				$item = 2;
			}

			$data["outcomes"][$item]["data"][$key] = (int) $row->value;
		}

		return view('charts.line_graph', $data);
	}

	public function age()
	{
		$q = Lookup::groupby_query();
		$date_query = Lookup::date_query();
		$divisions_query = Lookup::divisions_query();
		$groupby = session('filter_groupby', 1);

		if($groupby != 12) $date_query = Lookup::year_month_query();
		else if($groupby > 9) return null;

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->join('surge_columns_view', 'surge_columns_view.id', '=', "{$this->my_table}.column_id")
			->selectRaw($q['select_query'] . ", age_name, SUM(value) AS value ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->whereRaw(Lookup::surge_columns_query(false, true, false))
			->groupBy($q['group_array'][0], 'age_name')
			->when(sizeof($q['group_array']) == 2, function($query) use($q){
				return $query->groupBy($q['group_array'][1]);
			})
			->when(true, function($query) use($groupby, $q) {
				if($groupby < 10) return $query->orderBy('div_id');
				return $query->orderBy($q['select_query']);
			})
			->orderBy('age_id')
			->get();

		$ages = SurgeAge::tx()->get();

		$bars = $ages->pluck(['age_name'])->toArray();

		$data['div'] = str_random(15);
		$data['suffix'] = '';
		$data['yAxis'] = 'Number of Clients';

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
		ini_set('memory_limit', '-1');
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

		$sql = "countyname as County, Subcounty,		
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name`, facilitycode AS `MFL Code`, 
		name AS `Facility`, alias_name AS `Column Name`, value AS `Value`";

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->join('surge_columns_view', "{$this->my_table}.column_id", '=', 'surge_columns_view.id')
			->selectRaw($sql)
			->where(['partner' => $partner->id, 'financial_year' => $financial_year, 'month' => $month, 'modality' => 'tx_curr'])
			->when($age_category_id, function($query) use ($age_category_id){
				return $query->where('age_category_id', $age_category_id);
			})
			->when($gender_id, function($query) use ($gender_id){
				return $query->where('gender_id', $gender_id);
			})
			->orderBy('view_facilities.name', 'asc')
			->orderBy('column_id', 'asc')
			->get();

		$filename = str_replace(' ', '_', $partner->name) . '_FY_' . $financial_year . '_' . Lookup::resolve_month($month) . '_tx_curr';

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

		$m = SurgeModality::where(['modality' => 'tx_curr'])->first();
		$columns = SurgeColumn::where(['modality_id' => $m->id])->get();
		$periods = Period::where('year', '>', 2018)->get();

		$today = date('Y-m-d');

		foreach ($data as $row_key => $row){
			$hasdata = false;

			$col = $columns->where('alias_name', $row->column_name)->first();
			$p = $periods->where('financial_year', $row->financial_year)->where('month', $row->month)->first();
			$val = (int) $row->value;
			if(!$col || !$val || !$p) continue;

			if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) continue;
			$fac = Facility::where('facilitycode', $row->mfl_code)->first();

			if(!$fac) continue;
			$update_data = ['dateupdated' => $today, 'value' => $val]; 

			DB::table($this->my_table)->where(['facility' => $fac->id, 'period_id' => $p->id, 'column_id' => $c->id])->update($update_data);
		}
		session(['toast_message' => 'The updates have been made.']);
		return back();

	}
}
