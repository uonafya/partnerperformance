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
use App\AgeCategory;
use App\Week;

class WeeklyController extends Controller
{
	private $my_table = 'd_weeklies';


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

		$sql = "countyname as County, Subcounty,
		facilitycode AS `MFL Code`, name AS `Facility`,
		financial_year AS `Financial Year`, year AS `Calendar Year`, week_number as `Week Number`, 
		alias_name AS `Column Name`, value AS `Value`";

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback_weeks($this->my_table))
			->join('surge_columns_view', "{$this->my_table}.column_id", '=', 'surge_columns_view.id')
			->selectRaw($sql)
			->where(['partner' => $partner->id, 'week_id' => $week_id, 'modality' => $m_name])
			->orderBy('view_facilitys.name', 'asc')
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
