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
use App\Period;

class TxCurrentController extends Controller
{
	private $my_table = 'd_tx_curr';
	private $my_conditions = ['modality' => 'tx_curr'];


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

		$sql = "countyname as County, Subcounty,		
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name`, facilitycode AS `MFL Code`, 
		name AS `Facility`, alias_name AS `Column Name`, value AS `Value`";

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->join('surge_columns_view', "{$this->my_table}.column_id", '=', 'surge_columns_view.id')
			->selectRaw($sql)
			->where(['partner' => $partner->id, 'financial_year' => $financial_year, 'month' => $month, 'modality' => 'tx_curr'])
			->orderBy('view_facilitys.name', 'asc')
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
