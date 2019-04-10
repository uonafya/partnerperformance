<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;

use App\Week;
use App\SurgeAge;
use App\SurgeGender;
use App\SurgeModality;
use App\SurgeColumn;
use App\SurgeColumnView;
// use App\Surge;

class SurgeController extends Controller
{


	public function download_excel(Request $request)
	{
		$partner = session('session_partner');
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}
		$data = [];

		$week_id = $request->input('week');
		$modalities = $request->input('modalities');
		$gender = $request->input('gender');

		$columns = SurgeColumn::when(true, function($query) use ($modalities){
			if(is_array($modalities)) return $query->whereIn('modality_id', $modalities);
			return $query->where('modality_id', $modalities);
		})->when($gender, function($query) use ($gender){
			return $query->where('gender_id', $gender);
		})
		->orderBy('modality_id', 'asc')
		->orderBy('gender_id', 'asc')
		->orderBy('age_id', 'asc')
		->get();

		$sql = "County, Subcounty, facilitycode AS `MFL Code`, name AS `Facility`, financial_year AS `Financial Year`, week_number as `Week Number`";

		foreach ($columns as $column) {
			$sql .= ", `{$column->column_name}` AS `{$column->alias_name}`";
		}

		$week = Week::find($week_id);
		$filename = str_replace(' ', '_', strtolower($partner->name)) . '_surge_data_for_' . $week->start_date . '_to_' . $week->end_date;
		
		$rows = DB::table('d_surge')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_surge.facility')
			->join('weeks', 'weeks.id', '=', 'd_surge.week_id')
			->selectRaw($sql)
			->where('week_id', $week_id)
			->where('partner', $partner->id)
			->orderBy('name', 'asc')
			->get();

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
}
