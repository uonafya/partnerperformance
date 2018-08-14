<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;

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
			// ->whereRaw($date_query)
			->whereRaw($divisions_query)
			->where('viremia_beneficiaries', '>', 0)
			->where('financial_year', '>', 2016)
			->groupBy('financial_year')
			->orderBy('financial_year', 'asc')
			->get();

		$dsd = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw($select_query)
			->whereRaw($divisions_query)
			->where('dsd_beneficiaries', '>', 0)
			->where('financial_year', '>', 2016)
			->groupBy('financial_year')
			->orderBy('financial_year', 'asc')
			->get();

		$otz = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw($select_query)
			->whereRaw($divisions_query)
			->where('otz_beneficiaries', '>', 0)
			->where('financial_year', '>', 2016)
			->groupBy('financial_year')
			->orderBy('financial_year', 'asc')
			->get();

		$men = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw($select_query)
			->whereRaw($divisions_query)
			->where('men_clinic_beneficiaries', '>', 0)
			->where('financial_year', '>', 2016)
			->groupBy('financial_year')
			->orderBy('financial_year', 'asc')
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

		$data['categories'][0] = "FY 2017";
		$data['categories'][1] = "FY 2018";
		$data['categories'][2] = "FY 2019";

		$data["outcomes"][0]["data"] = array_fill(0, 3, 0);
		$data["outcomes"][1]["data"] = array_fill(0, 3, 0);
		$data["outcomes"][2]["data"] = array_fill(0, 3, 0);
		$data["outcomes"][3]["data"] = array_fill(0, 3, 0);

		foreach ($viremia as $key => $row) {
			$data['categories'][$key] = "FY " . $row->financial_year;
			$data["outcomes"][0]["data"][$key] = (int) $row->total;
			$data["outcomes"][1]["data"][$key] = (int) $dsd[$key]->total;
			$data["outcomes"][2]["data"][$key] = (int) $otz[$key]->total;
			$data["outcomes"][3]["data"][$key] = (int) $men[$key]->total;
		}
		return view('charts.bar_graph', $data);		
	}

	public function beneficiaries()
	{
		// $date_query = Lookup::date_query(true);
		$divisions_query = Lookup::divisions_query();

		$viremia = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year, SUM(viremia_beneficiaries) AS beneficiaries, SUM(viremia_target) AS target ")
			// ->whereRaw($date_query)
			->whereRaw($divisions_query)
			// ->where('viremia_beneficiaries', '>', 0)
			->where('financial_year', '>', 2016)
			->groupBy('financial_year')
			->orderBy('financial_year', 'asc')
			->get();

		$dsd = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year, SUM(dsd_beneficiaries) AS beneficiaries, SUM(dsd_target) AS target ")
			->whereRaw($divisions_query)
			// ->where('dsd_beneficiaries', '>', 0)
			->where('financial_year', '>', 2016)
			->groupBy('financial_year')
			->orderBy('financial_year', 'asc')
			->get();

		$otz = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year, SUM(otz_beneficiaries) AS beneficiaries, SUM(otz_target) AS target ")
			->whereRaw($divisions_query)
			// ->where('otz_beneficiaries', '>', 0)
			->where('financial_year', '>', 2016)
			->groupBy('financial_year')
			->orderBy('financial_year', 'asc')
			->get();

		$men = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year, SUM(men_clinic_beneficiaries) AS beneficiaries, SUM(men_clinic_target) AS target ")
			->whereRaw($divisions_query)
			->where('men_clinic_beneficiaries', '>', 0)
			->where('financial_year', '>', 2016)
			->groupBy('financial_year')
			->orderBy('financial_year', 'asc')
			->get();

		$data['div'] = str_random(15);
		// $data['stacking_false'] = false;

		$data['outcomes'][0]['name'] = "Viremia Beneficiaries";
		$data['outcomes'][1]['name'] = "DSD Beneficiaries";
		$data['outcomes'][2]['name'] = "OTZ Beneficiaries";
		$data['outcomes'][3]['name'] = "Men Clinics Beneficiaries";

		$data['outcomes'][4]['name'] = "Viremia Shortfall";
		$data['outcomes'][5]['name'] = "DSD Shortfall";
		$data['outcomes'][6]['name'] = "OTZ Shortfall";
		$data['outcomes'][7]['name'] = "Men Clinics Shortfall";

		$data['outcomes'][0]['stack'] = "Viremia";
		$data['outcomes'][1]['stack'] = "DSD";
		$data['outcomes'][2]['stack'] = "OTZ";
		$data['outcomes'][3]['stack'] = "Men";

		$data['outcomes'][4]['stack'] = "Viremia";
		$data['outcomes'][5]['stack'] = "DSD";
		$data['outcomes'][6]['stack'] = "OTZ";
		$data['outcomes'][7]['stack'] = "Men";


		foreach ($viremia as $key => $row) {
			$data['categories'][$key] = "FY " . $row->financial_year;
			$data["outcomes"][0]["data"][$key] = (int) $row->beneficiaries;
			$data["outcomes"][1]["data"][$key] = (int) $dsd[$key]->beneficiaries;
			$data["outcomes"][2]["data"][$key] = (int) $otz[$key]->beneficiaries;
			$data["outcomes"][3]["data"][$key] = (int) $men[$key]->beneficiaries;


			$data["outcomes"][4]["data"][$key] = ($row->target > $row->beneficiaries ? ($row->target-$row->beneficiaries) : 0);
			$data["outcomes"][5]["data"][$key] = ($dsd[$key]->target > $dsd[$key]->beneficiaries ? ($dsd[$key]->target-$dsd[$key]->beneficiaries) : 0);
			$data["outcomes"][6]["data"][$key] = ($otz[$key]->target > $otz[$key]->beneficiaries ? ($otz[$key]->target-$otz[$key]->beneficiaries) : 0);
			$data["outcomes"][7]["data"][$key] = ($men[$key]->target > $men[$key]->beneficiaries ? ($men[$key]->target-$men[$key]->beneficiaries) : 0);
		}
		return view('charts.bar_graph', $data);		
	}

	public function achievement()
	{
		$divisions_query = Lookup::divisions_query();

		$rows = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year,
			 SUM(viremia_beneficiaries) AS viremia_beneficiaries, SUM(viremia_target) AS viremia_target,
			 SUM(dsd_beneficiaries) AS dsd_beneficiaries, SUM(dsd_target) AS dsd_target, 
			 SUM(otz_beneficiaries) AS otz_beneficiaries, SUM(otz_target) AS otz_target, 
			 SUM(men_clinic_beneficiaries) AS men_clinic_beneficiaries, SUM(men_clinic_target) AS men_clinic_target ")
			->whereRaw($divisions_query)
			->where('financial_year', '>', 2016)
			->groupBy('financial_year')
			->orderBy('financial_year', 'asc')
			->get();	

		$data['div'] = str_random(15);
		// $data['stacking_false'] = false;

		$data['outcomes'][0]['name'] = "Viremia Beneficiaries";
		$data['outcomes'][1]['name'] = "DSD Beneficiaries";
		$data['outcomes'][2]['name'] = "OTZ Beneficiaries";
		$data['outcomes'][3]['name'] = "Men Clinics Beneficiaries";

		$data['outcomes'][4]['name'] = "Viremia Shortfall";
		$data['outcomes'][5]['name'] = "DSD Shortfall";
		$data['outcomes'][6]['name'] = "OTZ Shortfall";
		$data['outcomes'][7]['name'] = "Men Clinics Shortfall";

		$data['outcomes'][0]['stack'] = "Viremia";
		$data['outcomes'][1]['stack'] = "DSD";
		$data['outcomes'][2]['stack'] = "OTZ";
		$data['outcomes'][3]['stack'] = "Men";

		$data['outcomes'][4]['stack'] = "Viremia";
		$data['outcomes'][5]['stack'] = "DSD";
		$data['outcomes'][6]['stack'] = "OTZ";
		$data['outcomes'][7]['stack'] = "Men";

		for ($i=0; $i < 8; $i++) { 
			$data['outcomes'][$i]['type'] = "column";
		}

		foreach ($rows as $key => $row) {
			$data['categories'][$key] = "FY " . $row->financial_year;
			$data["outcomes"][0]["data"][$key] = (int) $row->viremia_beneficiaries;
			$data["outcomes"][1]["data"][$key] = (int) $row->dsd_beneficiaries;
			$data["outcomes"][2]["data"][$key] = (int) $row->otz_beneficiaries;
			$data["outcomes"][3]["data"][$key] = (int) $row->men_clinic_beneficiaries;


			$data["outcomes"][4]["data"][$key] = ($row->viremia_target > $row->viremia_beneficiaries ? ($row->viremia_target-$row->viremia_beneficiaries) : 0);
			$data["outcomes"][5]["data"][$key] = ($row->dsd_target > $row->dsd_beneficiaries ? ($row->dsd_target-$row->dsd_beneficiaries) : 0);
			$data["outcomes"][6]["data"][$key] = ($row->otz_target > $row->otz_beneficiaries ? ($row->otz_target-$row->otz_beneficiaries) : 0);
			$data["outcomes"][7]["data"][$key] = ($row->men_clinic_target > $row->men_clinic_beneficiaries ? ($row->men_clinic_target-$row->men_clinic_beneficiaries) : 0);
		}
		return view('charts.bar_graph', $data);		
	}

	public function breakdown()
	{
		$divisions_query = Lookup::divisions_query();
		$date_query = Lookup::date_query(true);
		$q = Lookup::groupby_query();

		$data['rows'] = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw($q['select_query'] . ",
			 SUM(viremia_beneficiaries) AS viremia_beneficiaries, SUM(viremia_target) AS viremia_target,
			 SUM(dsd_beneficiaries) AS dsd_beneficiaries, SUM(dsd_target) AS dsd_target, 
			 SUM(otz_beneficiaries) AS otz_beneficiaries, SUM(otz_target) AS otz_target, 
			 SUM(men_clinic_beneficiaries) AS men_clinic_beneficiaries, SUM(men_clinic_target) AS men_clinic_target ")
			->whereRaw($date_query)
			->whereRaw($divisions_query)
			->groupBy($q['group_query'])
			->get();

		$data['div'] = str_random(15);

		return view('combined.otz', $data);

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

		$rows = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 't_non_mer.facility')
			->selectRaw("financial_year, name, partnername, facilitycode, DHIScode, 
				is_viremia, is_dsd, is_otz, is_men_clinic,
				viremia_beneficiaries, dsd_beneficiaries, otz_beneficiaries, men_clinic_beneficiaries ")
			->when($financial_year, function($financial_year) use ($financial_year){
				return $query->where('financial_year', $financial_year);
			})
			->where('partner', $partner->id)			
			->orderBy('name', 'asc')
			->get();

		$filename = snake_case($partner->name) . '_' . $financial_year;

    	Excel::create($filename, function($excel) use($rows){
    		$excel->sheet('sheet1', function($sheet) use($rows){
    			$sheet->fromArray($rows);
    		});
    	})->store('csv');

    	$path = storage_path('exports/' . $filename . '.csv');
    	return response()->download($path);
	}



}
