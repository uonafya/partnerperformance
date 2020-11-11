<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;
use App\Period;
use App\Facility;
use App\ViewFacility;

class PNSController extends Controller
{
	private $my_table = 'd_pns';

	public function summary_chart()
	{
		$ages = $this->get_ages();
		$sql = '';

		$data['div'] = str_random(15);
		$data['stacking_false'] = true;
		$i=0;

		foreach ($this->item_array as $item => $name) {
			$data['outcomes'][$i]['name'] = $name;
			$data['outcomes'][$i]['type'] = 'column';
			$subsql = '(';
			foreach ($ages as $age) {
				$subsql .= "IFNULL(SUM({$item}_{$age}), 0) + ";
			}
			$subsql = substr($subsql, 0, -2);
			$subsql .= ") as {$item}, ";
			$sql .= $subsql;
			$i++;
		}
		$sql = substr($sql, 0, -2);

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('screened'))
			->having('screened', '>', 0)
			->get();


		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->screened;
			$data["outcomes"][1]["data"][$key] = (int) $row->contacts_identified;
			$data["outcomes"][2]["data"][$key] = (int) $row->pos_contacts;
			$data["outcomes"][3]["data"][$key] = (int) $row->eligible_contacts;
			$data["outcomes"][4]["data"][$key] = (int) $row->contacts_tested;
			$data["outcomes"][5]["data"][$key] = (int) $row->new_pos;
			$data["outcomes"][6]["data"][$key] = (int) $row->linked_haart;
		}	
		return view('charts.bar_graph', $data);
	}

	public function pns_contribution()
	{
    	$groupby = session('filter_groupby', 1);		

		$data['ages_array'] = $this->ages_array;

		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($this->get_table_query('new_pos'))
			->when(true, $this->get_callback('total'))
			->get();

		$rows2 = DB::table('m_testing')
			->when(true, $this->get_joins_callback('m_testing'))
			->selectRaw("SUM(positive_total) AS `pos` ")
			->when(true, $this->get_callback())
			->get();

		$data['div'] = str_random(15);

		$data['outcomes'][0]['name'] = "PNS New Positives";
		$data['outcomes'][1]['name'] = "DHIS Positives Less PNS";
		$data['outcomes'][2]['name'] = "PNS Contribution To Positives";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		// $data['outcomes'][2]['type'] = "spline";

		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' %');

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;

		Lookup::splines($data, [2]);

		$i = 0;

		foreach ($rows as $key => $row) {
			if($row->total == 0) continue;
			$data['categories'][$i] = Lookup::get_category($row);
			$dhis = (int) Lookup::get_val($row, $rows2, 'pos');
			$data["outcomes"][0]["data"][$i] = (int) $row->total;	
			$data["outcomes"][1]["data"][$i] = $dhis - $row->total;
			$data["outcomes"][2]["data"][$i] = Lookup::get_percentage($row->total, $dhis);
			$i++;
		}
		return view('charts.dual_axis', $data);
	}

	public function get_ages()
	{
		$ages = session('filter_pns_age', $this->mf_array);
		if($ages == [] || in_array('null', $ages)) $ages = $this->mf_array;
		return $ages;
	}

	public function get_table($item)
	{		
		$data = Lookup::table_data();
		$data['ages_array'] = $this->ages_array;

		$data['rows'] = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($this->get_table_query($item))
			->when(true, $this->get_callback('total'))
			->get();

		return view('tables.pns', $data);
	}

	public function summary_table()
	{
		$ages = $this->get_ages();
		$sql = '';

		$data = Lookup::table_data();
		$i=0;

		foreach ($this->item_array as $item => $name) {
			$data['outcomes'][$i]['name'] = $name;
			$data['outcomes'][$i]['type'] = 'column';
			$subsql = '(';
			foreach ($ages as $age) {
				$subsql .= "IFNULL(SUM({$item}_{$age}), 0) + ";
			}
			$subsql = substr($subsql, 0, -2);
			$subsql .= ") as {$item}, ";
			$sql .= $subsql;
			$i++;
		}
		$sql = substr($sql, 0, -2);

		$data['rows'] = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
			->selectRaw($sql)
			->when(true, $this->get_callback('contacts_identified'))
			->having('contacts_identified', '>', 0)
			->get();

		return view('tables.pns_summary', $data);


		foreach ($rows as $key => $row){
			$data['categories'][$key] = Lookup::get_category($row);

			$data["outcomes"][0]["data"][$key] = (int) $row->screened;
			$data["outcomes"][1]["data"][$key] = (int) $row->contacts_identified;
			$data["outcomes"][2]["data"][$key] = (int) $row->pos_contacts;
			$data["outcomes"][3]["data"][$key] = (int) $row->eligible_contacts;
			$data["outcomes"][4]["data"][$key] = (int) $row->contacts_tested;
			$data["outcomes"][5]["data"][$key] = (int) $row->new_pos;
			$data["outcomes"][6]["data"][$key] = (int) $row->linked_haart;
		}	
		return view('charts.bar_graph', $data);
	}

	public function get_table_query($item, $columns_array=null, $add_final=true)
	{
		$sql = '';
		$final = '(';
		if(!$columns_array) $columns_array = $this->ages_array;
		foreach ($columns_array as $key => $value) {
			if(is_numeric($key)) $key = $value;
			$sql .= "SUM({$item}_{$key}) AS {$key}, ";
			$final .= "IFNULL(SUM({$item}_{$key}), 0) + ";
		}
		$final = substr($final, 0, -2);
		$final .= ") as total ";
		if($add_final) $sql .= $final;
		return $sql;
	}

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

	public $male_array = ['below_15_m', 'below_20_m', 'below_25_m', 'below_30_m', 'below_50_m', 'above_50_m'];
	public $female_array = ['below_15_f', 'below_20_f', 'below_25_f', 'below_30_f', 'below_50_f', 'above_50_f'];

	public $mf_array = ['unknown_m', 'unknown_f', 'below_1', 'below_10', 'below_15_m', 'below_20_m', 'below_25_m', 'below_30_m', 'below_50_m', 'above_50_m',
	'below_15_f', 'below_20_f', 'below_25_f', 'below_30_f', 'below_50_f', 'above_50_f'];


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

		$sql = "countyname as County, Subcounty,
		facilitycode AS `MFL Code`, name AS `Facility`,
		financial_year AS `Financial Year`, year AS `Calendar Year`, month AS `Month`, 
		MONTHNAME(concat(year, '-', month, '-01')) AS `Month Name` ";

		foreach ($items as $item) {
			foreach ($this->ages_array as $key => $value) {
				$sql .= ", {$item}_{$key} AS `" . $this->item_array[$item] . " {$value}` ";
			}
		}

		$filename = str_replace(' ', '_', strtolower($partner->name)) . '_' . $financial_year . '_pns';
		
		$rows = DB::table($this->my_table)
			->when(true, $this->get_joins_callback($this->my_table))
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

		$today = date('Y-m-d');

		$columns = [];

		foreach ($this->item_array as $key => $value) {
			$str = str_replace(' ', '_', strtolower($value));
			foreach ($this->ages_array as $key2 => $value2) {
				$column_name = $key . '_' . $key2;
				$key_name = $str . '_' . str_replace(' ', '_', strtolower($value2));
				$key_name = str_replace('-', '_', $key_name);
				$columns[$key_name] = $column_name;
			}
		}

		$stuff = [];

		foreach ($data as $row_key => $row){
			if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) continue;
			$fac = Facility::where('facilitycode', $row->mfl_code)->first();
			if(!$fac) continue;
			// if($fac->partner != $partner->id){
			// 	$fac->partner = $partner->id;
			// 	$fac->save();

			// 	DB::table('apidb.facilitys')->where('facilitycode', $fac->facilitycode)->update(['partner' => $partner->id]);
			// 	DB::table('national_db.facilitys')->where('facilitycode', $fac->facilitycode)->update(['partner' => $partner->id]);
			// }
			$hasdata = false;
			$update_data = ['dateupdated' => $today];
			foreach ($row as $key => $value) {
				if(isset($columns[$key])){
					$update_data[$columns[$key]] = (int) $value;
					if(((int) $value) > 0) $hasdata = true;
				}
			}

			if($hasdata && !$fac->is_pns){
				$fac->is_pns = 1;
				$fac->save();
			}

			$period = Period::where(['financial_year' => $row->financial_year, 'month' => $row->month])->first();
			if(!$period) continue;

			DB::connection('mysql_wr')->table('d_pns')
				->where(['facility' => $fac->id, 'period_id' => $period->id, ])
				->update($update_data);

		}

		session(['toast_message' => "The updates have been made."]);
		return back();
	}

	public function upload_facilities(Request $request)
	{
		ini_set('memory_limit', '-1');
		if (!$request->hasFile('upload')){
	        session(['toast_message' => 'Please select a file before clicking the submit button.']);
	        session(['toast_error' => 1]);
			return back();
		}
		$file = $request->upload->path();

		if(auth()->user()->user_type_id != 1) return back();

		$data = Excel::load($file, function($reader){
			$reader->toArray();
		})->get();

		$partner = $request->input('partner_id');

		$mflcodes = [];

		foreach ($data as $key => $row) {
			$mflcodes[] = $row->mfl_code;
		}

		// dd($mflcodes);

		DB::table('facilitys')->whereIn('facilitycode', $mflcodes)->update(['partner' => $partner]);
		DB::table('apidb.facilitys')->whereIn('facilitycode', $mflcodes)->update(['partner' => $partner]);
		DB::table('national_db.facilitys')->whereIn('facilitycode', $mflcodes)->update(['partner' => $partner]);


		session(['toast_message' => "The updates have been made."]);
		return back();
	}
}
