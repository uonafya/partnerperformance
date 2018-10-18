<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;
use App\Facility;
use App\ViewFacility;

class PNSController extends Controller
{

	public $item_array = [
		'screened' => 'Index Clients Screened',
		'contacts_identified' => 'Contacts Identified',
		'pos_contacts' => 'Known HIV Positive Contacts',
		'eligible_contacts' => 'Eligible Contacts',
		'contacts_tested' => 'Contacts Tested',
		'new_pos' => 'Newly Identified Positives',
		'linked_haart' => 'Linked To HAART',
	];

	public $ages_array = [
		'unknown_m' => 'Unknown Male',
		'unknown_f' => 'Unknown Female',
		'below_1' => 'Below 1',
		'below_10' => '1-9',
		'below_15_m' => '10-14 Male',
		'below_15_f' => '10-14 Female',
		'below_20_m' => '15-19 Male',
		'below_20_f' => '15-19 Female',
		'below_25_m' => '20-24 Male',
		'below_25_f' => '20-24 Female',
		'below_30_m' => '25-29 Male',
		'below_30_f' => '25-29 Female',
		'below_50_m' => '30-49 Male',
		'below_50_f' => '30-49 Female',
		'above_50_m' => 'Above 50 Male',
		'above_50_f' => 'Above 50 Female',
	];

	public function download_excel(Request $request)
	{
		$partner = session('session_partner');
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}
		$data = [];

		$items = $request->input('items');
		$months = $request->input('months');
		$financial_year = $request->input('financial_year', 2018);

		$sql = "facilitycode AS `MFL Code`, name AS `Facility`, new_name, 
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name` ";

		foreach ($items as $item) {
			foreach ($this->ages_array as $key => $value) {
				$sql .= ", {$item}_{$key} AS `" . $this->item_array[$item] . " {$value}` ";
			}
		}

		$filename = str_replace(' ', '_', strtolower($partner->name)) . '_' . $financial_year . '_pns';
		
		$rows = DB::table('d_pns')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_pns.facility')
			->selectRaw($sql)
			->when($months, function($query) use ($months){
				return $query->whereIn('month', $months);
			})
			->where('financial_year', $financial_year)
			->where('partner', $partner->id)
			->orderBy('name', 'asc')
			->orderBy('d_pns.id', 'asc')
			->get();

		foreach ($rows as $row) {
			if(!$row->Facility) $row->Facility = $row->new_name;
			unset($row->new_name);
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
	        session(['toast_message' => 'Please select a file before clicking the submit button.']);
	        session(['toast_error' => 1]);
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

		$today = date('Y-m-d');

		$columns = [];

		foreach ($this->item_array as $key => $value) {
			$str = str_replace(' ', '_', strtolower($value));
			foreach ($this->ages_array as $key2 => $value2) {
				$column_name = $key . '_' . $key2;
				$key_name = $str . '_' . str_replace(' ', '_', strtolower($value2));
				$columns[$key_name] = $column_name;
			}
		}

		foreach ($data as $row){
			$fac = Facility::where('facilitycode', $row->mfl_code)->first();
			if(!$fac) continue;
			$update_data = ['dateupdated' => $today];
			foreach ($row as $key => $value) {
				if(isset($columns[$key])) $update_data[$columns[$key]] = (int) $value;
			}

			DB::connection('mysql_wr')->table('d_pns')
				->where(['facility' => $fac->id, 'year' => $row->calendar_year, 'month' => $row->month])
				->update($update_data);
		}

		session(['toast_message' => "The updates have been made."]);
		return back();
	}
}
