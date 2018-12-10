<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;
use App\Facility;
use App\ViewFacility;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// pns partner notification services

class OtzController extends Controller
{

	public function facilities_count()
	{
		// $date_query = Lookup::date_query(true);
		$divisions_query = Lookup::divisions_query();

		$select_query = "financial_year, COUNT(DISTINCT t_non_mer.facility) AS total ";

		$viremia = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw($select_query)
			->when(true, $this->get_callback())
			->where('viremia_beneficiaries', '>', 0)
			->where('financial_year', '>', 2017)
			->get();

		$dsd = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw($select_query)
			->when(true, $this->get_callback())
			->where('dsd_beneficiaries', '>', 0)
			->where('financial_year', '>', 2017)
			->get();

		$otz = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw($select_query)
			->when(true, $this->get_callback())
			->where('otz_beneficiaries', '>', 0)
			->where('financial_year', '>', 2017)
			->get();

		$men = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw($select_query)
			->when(true, $this->get_callback())
			->where('men_clinic_beneficiaries', '>', 0)
			->where('financial_year', '>', 2017)
			->get();

		$data['div'] = str_random(15);
		$data['stacking_false'] = false;

		$data['outcomes'][0]['name'] = "Viremia Facilities";
		$data['outcomes'][1]['name'] = "DSD Facilities";
		$data['outcomes'][2]['name'] = "OTZ Facilities";
		$data['outcomes'][3]['name'] = "Men Clinics";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "column";

		foreach ($dsd as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = Lookup::get_val($row, $viremia, 'total');
			$data["outcomes"][1]["data"][$key] = (int) $row->total;
			$data["outcomes"][2]["data"][$key] = Lookup::get_val($row, $otz, 'total');
			$data["outcomes"][3]["data"][$key] = Lookup::get_val($row, $men, 'total');
		}		
		return view('charts.bar_graph', $data);		
	}

	public function clinics()
	{
		// $date_query = Lookup::date_query(true);
		$divisions_query = Lookup::divisions_query();

		$select_query = "COUNT(id) AS total ";

		$viremia = DB::table('view_facilitys')
			->selectRaw($select_query)
			->whereRaw($divisions_query)
			->where('is_viremia', 1)
			->first();

		$dsd = DB::table('view_facilitys')
			->selectRaw($select_query)
			->whereRaw($divisions_query)
			->where('is_dsd', 1)
			->first();

		$otz = DB::table('view_facilitys')
			->selectRaw($select_query)
			->whereRaw($divisions_query)
			->where('is_otz', 1)
			->first();

		$men = DB::table('view_facilitys')
			->selectRaw($select_query)
			->whereRaw($divisions_query)
			->where('is_men_clinic', 1)
			->first();

		$data['div'] = str_random(15);
		$data['stacking_false'] = false;

		$data['categories'][0] = "Viremia Facilities";
		$data['categories'][1] = "DSD Facilities";
		$data['categories'][2] = "OTZ Facilities";
		$data['categories'][3] = "Men Clinics";

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][0]['name'] = "Total number of clinics";

		$data["outcomes"][0]["data"][0] = (int) $viremia->total ?? 0;
		$data["outcomes"][0]["data"][1] = (int) $dsd->total ?? 0;
		$data["outcomes"][0]["data"][2] = (int) $otz->total ?? 0;
		$data["outcomes"][0]["data"][3] = (int) $men->total ?? 0;

		if(!str_contains($divisions_query, ['county', 'ward_id', 'view_facilitys'])){

			$financial_year = session('filter_financial_year');

			$targets = DB::table('p_non_mer')
				->leftJoin('partners', 'partners.id', '=', 'p_non_mer.partner')
				->selectRaw("SUM(viremia) AS viremia, SUM(dsd) AS dsd, SUM(otz) AS otz, SUM(men_clinic) AS men_clinic ")
				->whereRaw($divisions_query)
				->where('financial_year', $financial_year)
				->first();

			$data['outcomes'][1]['type'] = "spline";
			$data['outcomes'][1]['name'] = "Targeted number of clinics";

			$data['outcomes'][1]['lineWidth'] = 0;
			$data['outcomes'][1]['marker'] = ['enabled' => true, 'radius' => 4];
			$data['outcomes'][1]['states'] = ['hover' => ['lineWidthPlus' => 0]];
			// $data['outcomes'][1]['color'] = "#000000";

			$data["outcomes"][1]["data"][0] = (int) $targets->viremia ?? 0;
			$data["outcomes"][1]["data"][1] = (int) $targets->dsd ?? 0;
			$data["outcomes"][1]["data"][2] = (int) $targets->otz ?? 0;
			$data["outcomes"][1]["data"][3] = (int) $targets->men_clinic ?? 0;

		}

		return view('charts.bar_graph', $data);		
	}

	public function beneficiaries()
	{
		$date_query = Lookup::date_query(true);

		$viremia = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year, SUM(viremia_beneficiaries) AS beneficiaries, SUM(viremia_target) AS target ")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->where('financial_year', '>', 2016)
			->get();

		$dsd = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year, SUM(dsd_beneficiaries) AS beneficiaries, SUM(dsd_target) AS target ")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->where('financial_year', '>', 2016)
			->get();

		$otz = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year, SUM(otz_beneficiaries) AS beneficiaries, SUM(otz_target) AS target ")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->where('financial_year', '>', 2016)
			->get();

		$men = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year, SUM(men_clinic_beneficiaries) AS beneficiaries, SUM(men_clinic_target) AS target ")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->where('financial_year', '>', 2016)
			->get();

		$data['div'] = str_random(15);
		$data['stacking_false'] = false;

		$data['outcomes'][0]['name'] = "Viremia Beneficiaries";
		$data['outcomes'][1]['name'] = "DSD Beneficiaries";
		$data['outcomes'][2]['name'] = "OTZ Beneficiaries";
		$data['outcomes'][3]['name'] = "Men Clinics Beneficiaries";

		foreach ($dsd as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = Lookup::get_val($row, $viremia, 'beneficiaries');
			$data["outcomes"][1]["data"][$key] = (int) $row->beneficiaries;
			$data["outcomes"][2]["data"][$key] = Lookup::get_val($row, $otz, 'beneficiaries');
			$data["outcomes"][3]["data"][$key] = Lookup::get_val($row, $men, 'beneficiaries');
		}		
		return view('charts.bar_graph', $data);		
	}

	public function achievement()
	{
		$date_query = Lookup::date_query(true);

		$rows = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year,
			 SUM(viremia_beneficiaries) AS viremia_beneficiaries, SUM(viremia_target) AS viremia_target,
			 SUM(dsd_beneficiaries) AS dsd_beneficiaries, SUM(dsd_target) AS dsd_target, 
			 SUM(otz_beneficiaries) AS otz_beneficiaries, SUM(otz_target) AS otz_target, 
			 SUM(men_clinic_beneficiaries) AS men_clinic_beneficiaries, SUM(men_clinic_target) AS men_clinic_target ")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->where('financial_year', '>', 2016)
			->get();	

		$data['div'] = str_random(15);
		$data['stacking_false'] = false;

		$data['outcomes'][0]['name'] = "Viremia Beneficiaries";
		$data['outcomes'][1]['name'] = "DSD Beneficiaries";
		$data['outcomes'][2]['name'] = "OTZ Beneficiaries";
		$data['outcomes'][3]['name'] = "Men Clinics Beneficiaries";

		for ($i=0; $i < 4; $i++) { 
			$data['outcomes'][$i]['type'] = "column";
		}

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = Lookup::get_category($row);
			$data["outcomes"][0]["data"][$key] = (int) $row->viremia_beneficiaries;
			$data["outcomes"][1]["data"][$key] = (int) $row->dsd_beneficiaries;
			$data["outcomes"][2]["data"][$key] = (int) $row->otz_beneficiaries;
			$data["outcomes"][3]["data"][$key] = (int) $row->men_clinic_beneficiaries;

		}
		return view('charts.bar_graph', $data);		
	}

	public function breakdown()
	{
		$divisions_query = Lookup::divisions_query();
		$date_query = Lookup::date_query(true);
		$q = Lookup::groupby_query();

		$select_query = $q['select_query'];

		if(session('filter_groupby') == 5) $select_query .= ", is_viremia, is_dsd, is_otz, is_men_clinic";

		$data['rows'] = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw($select_query . ",
			 SUM(viremia_beneficiaries) AS viremia_beneficiaries, SUM(viremia_target) AS viremia_target,
			 SUM(dsd_beneficiaries) AS dsd_beneficiaries, SUM(dsd_target) AS dsd_target, 
			 SUM(otz_beneficiaries) AS otz_beneficiaries, SUM(otz_target) AS otz_target, 
			 SUM(men_clinic_beneficiaries) AS men_clinic_beneficiaries, SUM(men_clinic_target) AS men_clinic_target ")
			->when(true, $this->get_callback())
			->whereRaw($date_query)
			->get();

		$data['div'] = str_random(15);

		return view('combined.otz', $data);

	}

	public function clinic_setup()
	{
		$divisions_query = Lookup::divisions_query();
		$date_query = Lookup::date_query(true);
		$q = Lookup::groupby_query();

		$select_query = $q['select_query'] . ", count(id) as total ";

		$data['viremia'] = DB::table('view_facilitys')
			->selectRaw($select_query)
			->where('is_viremia', 1)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['otz'] = DB::table('view_facilitys')
			->selectRaw($select_query)
			->where('is_otz', 1)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['dsd'] = DB::table('view_facilitys')
			->selectRaw($select_query)
			->where('is_dsd', 1)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['men_clinic'] = DB::table('view_facilitys')
			->selectRaw($select_query)
			->where('is_men_clinic', 1)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		if(!str_contains($divisions_query, ['county', 'ward_id', 'view_facilitys'])){

			$q = Lookup::groupby_query(false);

			$select_query = $q['select_query'] . ", SUM(viremia) AS viremia, SUM(dsd) AS dsd, SUM(otz) AS otz, SUM(men_clinic) AS men_clinic ";

			$data['targets'] = DB::table('p_non_mer')
				->leftJoin('partners', 'partners.id', '=', 'p_non_mer.partner')
				->selectRaw($select_query)
				->whereRaw($date_query)
				->whereRaw($divisions_query)
				->groupBy($q['group_query'])
				->get();
		}

		$data['div'] = str_random(15);

		return view('combined.clinic_setup', $data);
	}

	public function otz_breakdown()
	{
		$divisions_query = Lookup::divisions_query();
		$date_query = Lookup::apidb_date_query();
		$q = Lookup::groupby_query();

		$data['rows'] = DB::table("apidb.vl_site_suppression")
			->join('hcm.view_facilitys', 'view_facilitys.id', '=', 'vl_site_suppression.facility')
			->selectRaw($q['select_query'] . ", count(view_facilitys.id) as `facilities`,
				SUM(`less14_suppressed`) as `less14_suppressed`, SUM(`less14_nonsuppressed`) as `less14_nonsuppressed`, 
				SUM(`less19_suppressed`) as `less19_suppressed`, SUM(`less19_nonsuppressed`) as `less19_nonsuppressed` 
				")
			->where('is_otz', 1)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		// $data['suppression_rows'] = DB::table("apidb.vl_site_summary")
		// 	->join('hcm.view_facilitys', 'view_facilitys.id', '=', 'vl_site_suppression.facility')
		// 	->selectRaw($q['select_query'] . ", count(view_facilitys.id) as `facilities`,
		// 		SUM(`less14_suppressed`) as `less14_suppressed`, SUM(`less14_nonsuppressed`) as `less14_nonsuppressed`, 
		// 		SUM(`less19_suppressed`) as `less19_suppressed`, SUM(`less19_nonsuppressed`) as `less19_nonsuppressed` 
		// 		")
		// 	->where('is_otz', 1)
		// 	->whereRaw($divisions_query)
		// 	->groupBy($q['group_query'])
		// 	->get();

		$data['div'] = str_random(15);
		$data['current_range'] = Lookup::get_current_header();

		return view('combined.otz_impact', $data);
	}

	public function dsd_impact()
	{
		return $this->impacts('is_dsd', 'tables.dsd_coverage');
	}

	public function mens_impact()
	{
		return $this->impacts('is_men_clinic', 'tables.men_clinic_coverage');
	}

	public function impacts($col, $return_view)
	{
		$data = Lookup::table_data();

		$data['rows'] = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("count(*) as facilities,
			 SUM(dsd_beneficiaries) AS dsd_beneficiaries, SUM(dsd_target) AS dsd_target, 
			 SUM(men_clinic_beneficiaries) AS men_clinic_beneficiaries, SUM(men_clinic_target) AS men_clinic_target ")
			->where($col, 1)
			->when(true, $this->target_callback())
			->get();

		$date_query = Lookup::year_month_query(6);
		$data['current_range'] = Lookup::year_month_name();
		$divisions_query = Lookup::divisions_query();
		$q = Lookup::groupby_query();

		$sql = " 
		(SUM(`current_below15_m`) + SUM(`current_below20_m`) + SUM(`current_below25_m`) + SUM(`current_above25_m`)) AS `males`,
		SUM(`current_total`) as total
		";	

		$data['art'] = DB::table('m_art')
			->join('view_facilitys', 'view_facilitys.id', '=', 'm_art.facility')
			->selectRaw($sql)
			->where($col, 1)
			->whereRaw($date_query)
			->when(true, $this->get_callback('total'))
			->get();

		return view($return_view, $data);
	}









	public function get_data(Request $request)
	{
		$target = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("t_non_mer.*, name")
			->where('view_facilitys.id', $request->input('facility_id'))
			->where('financial_year', $request->input('financial_year'))
			->first();

		return json_encode($target);

		// return view('partials.targets', ['targets' => $targets]);
	}

	public function set_target(Request $request)
	{
		$financial_year = $request->input('financial_year');

		// $facilities = $request->input('facilities');
		$facility_id = $request->input('facility_id');
		$viremia_beneficiaries = $request->input('viremia_beneficiaries');
		$viremia_target = $request->input('viremia_target');
		$dsd_beneficiaries = $request->input('dsd_beneficiaries');
		$dsd_target = $request->input('dsd_target');
		$otz_beneficiaries = $request->input('otz_beneficiaries');
		$otz_target = $request->input('otz_target');
		$men_clinic_beneficiaries = $request->input('men_clinic_beneficiaries');
		$men_clinic_target = $request->input('men_clinic_target');

		$today = date('Y-m-d');
		DB::connection('mysql_wr')->table('t_non_mer')
			->where(['financial_year' => $financial_year, 'facility' => $facility_id])
			->update([
				'viremia_beneficiaries' => Lookup::clean_zero($viremia_beneficiaries),
				'viremia_target' => Lookup::clean_zero($viremia_target),
				'dsd_beneficiaries' => Lookup::clean_zero($dsd_beneficiaries),
				'dsd_target' => Lookup::clean_zero($dsd_target),
				'otz_beneficiaries' => Lookup::clean_zero($otz_beneficiaries),
				'otz_target' => Lookup::clean_zero($otz_target),
				'men_clinic_beneficiaries' => Lookup::clean_zero($men_clinic_beneficiaries),
				'men_clinic_target' => Lookup::clean_zero($men_clinic_target),
				
				// 'viremia_beneficiaries' => $viremia_beneficiaries,
				// 'viremia_target' => $viremia_target,
				// 'dsd_beneficiaries' => $dsd_beneficiaries,
				// 'dsd_target' => $dsd_target,
				// 'otz_beneficiaries' => $otz_beneficiaries,
				// 'otz_target' => $otz_target,
				// 'men_clinic_beneficiaries' => $men_clinic_beneficiaries,
				// 'men_clinic_target' => $men_clinic_target,
			]);



		session(['toast_message' => 'The target has been updated.']);
		return back();

		// foreach ($facilities as $key => $facility) {
		// 	DB::where(['financial_year' => $financial_year, 'facility_id' => $facility])->update([
		// 		'viremia_beneficiaries' => Lookup::clean_zero($viremia_beneficiaries[$key]),
		// 		'viremia_target' => Lookup::clean_zero($viremia_target[$key]),
		// 		'dsd_beneficiaries' => Lookup::clean_zero($dsd_beneficiaries[$key]),
		// 		'dsd_target' => Lookup::clean_zero($dsd_target[$key]),
		// 		'otz_beneficiaries' => Lookup::clean_zero($otz_beneficiaries[$key]),
		// 		'otz_target' => Lookup::clean_zero($otz_target[$key]),
		// 		'men_clinic_beneficiaries' => Lookup::clean_zero($men_clinic_beneficiaries[$key]),
		// 		'men_clinic_target' => Lookup::clean_zero($men_clinic_target[$key]),
		// 	]);
		// }
	}

	public function download_excel($financial_year)
	{
		$partner = session('session_partner');
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}
		$data = [];

		$rows = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year AS `Financial Year`, name AS `Facility`, partnername AS `Partner Name`, facilitycode AS `MFL Code`, DHIScode AS `DHIS Code`, 
				subcounty AS `Subcounty Name`, `countyname` AS `County Name`, is_pns AS `Is PNS (YES/NO)`,
				is_viremia AS `Is Viremia (YES/NO)`, is_dsd AS `Is DSD (YES/NO)`, is_otz AS `Is OTZ (YES/NO)`, is_men_clinic AS `Is Men Clinic (YES/NO)`,
				viremia_beneficiaries AS `Viremia Beneficiaries`, dsd_beneficiaries AS `DSD Beneficiaries`, otz_beneficiaries AS `OTZ Beneficiaries`, men_clinic_beneficiaries AS `Men Clinic Beneficiaries` ")
			->when($financial_year, function($query) use ($financial_year){
				return $query->where('financial_year', $financial_year);
			})
			->where('partner', $partner->id)			
			->orderBy('name', 'asc')
			->get();

		foreach ($rows as $key => $row) {
			$row_array = get_object_vars($row);
			$data[] = $row_array;
			$data[$key]['Is PNS (YES/NO)'] = Lookup::get_boolean($row_array['Is PNS (YES/NO)']);
			$data[$key]['Is Viremia (YES/NO)'] = Lookup::get_boolean($row_array['Is Viremia (YES/NO)']);
			$data[$key]['Is DSD (YES/NO)'] = Lookup::get_boolean($row_array['Is DSD (YES/NO)']);
			$data[$key]['Is OTZ (YES/NO)'] = Lookup::get_boolean($row_array['Is OTZ (YES/NO)']);
			$data[$key]['Is Men Clinic (YES/NO)'] = Lookup::get_boolean($row_array['Is Men Clinic (YES/NO)']);
		}

		$filename = str_replace(' ', '_', strtolower($partner->name)) . '_non_mer_indicators_' . $financial_year;

    	Excel::create($filename, function($excel) use($data){
    		$excel->sheet('sheet1', function($sheet) use($data){
    			$sheet->fromArray($data);

	    		// $letter_array = ['F', 'G', 'H', 'I'];

	    		// for ($i=0; $i < $key; $i++) { 
	    		// 	foreach ($letter_array as $letter) {
	    		// 		$cell_no = $i+1;
	    		// 		// $sheet->
	    		// 		$objValidation = $sheet->getCell($letter . $cell_no)->getDataValidation();
	    		// 		$objValidation->setType('list');
	    		// 		$objValidation->setErrorStyle('information');
	    		// 		$objValidation->setAllowBlank(true);
	    		// 		$objValidation->setPromptTitle('Pick from list');
	    		// 		$objValidation->setPrompt('Please pick a value from the drop-down list.');
	    		// 		$objValidation->setFormula1('"YES,NO"');
	    		// 	}
	    		// }
    		});

    	})->store('xlsx');

    	$path = storage_path('exports/' . $filename . '.xlsx');
    	return response()->download($path);
	}



	public function upload_excel(Request $request)
	{
		if (!$request->hasFile('upload')){
	        session(['toast_message' => 'Please select a file before clicking the submit button.']);
	        session(['toast_error' => 1]);
			return back();
		}

		$file = $request->upload->path();
		// $path = $request->upload->store('public/results/vl');
		// $financial_year = $request->input('financial_year');

		$data = Excel::load($file, function($reader){
			$reader->toArray();
		})->get();

		$partner = session('session_partner');
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}
		$unidentified = 0;
		// print_r($data);die();

		foreach ($data as $key => $value) {
			if(!is_numeric($value->mfl_code) || (is_numeric($value->mfl_code) && $value->mfl_code < 10000)) continue;
			if(!isset($value->mfl_code)){
				session([
				'toast_message' => "This upload is incorrect. Please ensure that you are submitting on the right form.",
				'toast_error' => 1,
				]);
				return back();	
			}

			$fac = Facility::where('facilitycode', $value->mfl_code)->first();

			if(!$fac){
				$unidentified++;
				continue;
			}
			
			if($fac->partner != $partner->id){
				$fac->partner = $partner->id;
				$fac->save();

				DB::table('apidb.facilitys')->where('facilitycode', $fac->facilitycode)->update(['partner' => $partner->id]);
				DB::table('national_db.facilitys')->where('facilitycode', $fac->facilitycode)->update(['partner' => $partner->id]);
			}

			// if($fac->partner != auth()->user()->partner_id) continue;

			$fac->fill([
				'is_pns' => Lookup::clean_boolean($value->is_pns_yesno), 
				'is_viremia' => Lookup::clean_boolean($value->is_viremia_yesno), 
				'is_dsd' => Lookup::clean_boolean($value->is_dsd_yesno), 
				'is_otz' => Lookup::clean_boolean($value->is_otz_yesno), 
				'is_men_clinic' => Lookup::clean_boolean($value->is_men_clinic_yesno),
			]);

			$viremia = (int) $value->viremia_beneficiaries ?? null;
			$dsd = (int) $value->dsd_beneficiaries ?? null;
			$otz = (int) $value->otz_beneficiaries ?? null;
			$men_clinic = (int) $value->men_clinic_beneficiaries ?? null;

			DB::connection('mysql_wr')->table('t_non_mer')
				->where(['facility' => $fac->id, 'financial_year' => $value->financial_year])
				->update([
					'viremia_beneficiaries' => $viremia,
					'dsd_beneficiaries' => $dsd,
					'otz_beneficiaries' => $otz,
					'men_clinic_beneficiaries' => $men_clinic,
				]);

			if(!$fac->is_viremia && $viremia) $fac->is_viremia = 1;
			if(!$fac->is_dsd && $dsd) $fac->is_dsd = 1;
			if(!$fac->is_otz && $otz) $fac->is_otz = 1;
			if(!$fac->is_men_clinic && $men_clinic) $fac->is_men_clinic = 1;
			$fac->save();
		}

		session(['toast_message' => "The updates have been made. {$unidentified} facilities could not be found on our system."]);
		return back();
	}



}
