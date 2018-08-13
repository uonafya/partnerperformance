<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Lookup;

class TargetController extends Controller
{
	public function get_form($partner_id, $year)
	{
		$target = DB::table('t_non_mer')
			->join('view_facilitys', 'view_facilitys.id', '=', 'd_hiv_and_tb_treatment.facility')
			->selectRaw("t_non_mer.*, name")
			->where('partner', $partner_id)
			->where('financial_year', $year)
			->get();

		return $target;

		// return view('partials.targets', ['targets' => $targets]);
	}

	public function set_target(Request $request)
	{
		$financial_year = $request->input('financial_year');

		$facilities = $request->input('facilities');
		$viremia_beneficiaries = $request->input('viremia_beneficiaries');
		$viremia_target = $request->input('viremia_target');
		$dsd_beneficiaries = $request->input('dsd_beneficiaries');
		$dsd_target = $request->input('dsd_target');
		$otz_beneficiaries = $request->input('otz_beneficiaries');
		$otz_target = $request->input('otz_target');
		$men_clinic_beneficiaries = $request->input('men_clinic_beneficiaries');
		$men_clinic_target = $request->input('men_clinic_target');

		$today = date('Y-m-d');
		DB::where(['financial_year' => $financial_year, 'facility_id' => $facility])->update([
			'viremia_beneficiaries' => Lookup::clean_zero($viremia_beneficiaries),
			'viremia_target' => Lookup::clean_zero($viremia_target),
			'dsd_beneficiaries' => Lookup::clean_zero($dsd_beneficiaries),
			'dsd_target' => Lookup::clean_zero($dsd_target),
			'otz_beneficiaries' => Lookup::clean_zero($otz_beneficiaries),
			'otz_target' => Lookup::clean_zero($otz_target),
			'men_clinic_beneficiaries' => Lookup::clean_zero($men_clinic_beneficiaries),
			'men_clinic_target' => Lookup::clean_zero($men_clinic_target),
		]);

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
}
