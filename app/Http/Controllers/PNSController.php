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
	// vojiambo@usaid.gov



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
		'unknown_m' => 'unknown male',
		'unknown_f' => 'unknown female',
		'below_1' => 'below 1',
		'below_10' => '1-9',
		'below_15_m' => '10-14 male',
		'below_15_f' => '10-14 female',
		'below_20_m' => '15-19 male',
		'below_20_f' => '15-19 female',
		'below_25_m' => '20-24 male',
		'below_25_f' => '20-24 female',
		'below_30_m' => '25-29 male',
		'below_30_f' => '25-29 female',
		'below_50_m' => '30-49 male',
		'below_50_f' => '30-49 female',
		'above_50_m' => 'above 50 male',
		'above_50_f' => 'above 50 female',
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

		$sql = "facilitycode AS `MFL Code`, name AS `Facility`, 
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
