<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lookup;
use App\User;
use App\Week;
use App\Period;
use DB;

class GeneralController extends Controller
{

	public function partner_home()
	{
		session(['financial' => false]);
		$data = Lookup::partner_data();
		return view('base.partner_home', $data);
	}

	public function home()
	{
		$data = Lookup::view_data();
		return view('base.home', $data);
	}

	public function dupli_home()
	{
		$data = Lookup::view_data_surges();
		$data['weeks'] = \App\Week::where('financial_year', '>', 2020)->get();
		$financial_year = session('filter_financial_year');
		session(['filter_agency' => 1, 'filter_groupby' => 12]);
		return view('base.hfr', $data);
	}

	public function pmtct()
	{
		$data = Lookup::view_data();
		return view('base.pmtct', $data);
	}

	public function art()
	{
		$data = Lookup::view_data();
		return view('base.art', $data);
	}

	public function testing()
	{
		$data = Lookup::view_data();
		return view('base.testing', $data);
	}

	public function vmmc()
	{
		$data = Lookup::view_data();
		return view('base.vmmc', $data);
	}

	public function tb()
	{
		$data = Lookup::view_data();
		return view('base.tb', $data);
	}

	public function keypop()
	{
		$data = Lookup::view_data();
		return view('base.keypop', $data);
	}

	public function non_mer()
	{
		$data = Lookup::view_data();
		return view('base.non_mer', $data);
	}

	public function pns()
	{
		$data = Lookup::view_data();
		$data['ages'] = [
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
		return view('base.pns', $data);
	}

	public function surge()
	{
		$data = Lookup::view_data_surges();
		$financial_year = session('filter_financial_year');
		session(['filter_agency' => 1]);
		$data['display_date'] = ' (April, ' . ($financial_year) . ' - September ' . $financial_year . ')';
		return view('base.surge', $data);
	}

	public function dispensing()
	{
		$data = Lookup::view_data_surges();
		$financial_year = session('filter_financial_year');
		session(['filter_agency' => 1]);
		$data['display_date'] = ' (July, ' . ($financial_year) . ' - September ' . $financial_year . ')';
		return view('base.dispensing', $data);		
	}

	public function tx_curr()
	{
		$data = Lookup::view_data_surges();
		$data['ages'] = \App\SurgeAge::tx()->get();
		$financial_year = session('filter_financial_year');
		session(['filter_agency' => 1]);
		$data['display_date'] = ' (July, ' . ($financial_year) . ' - September ' . $financial_year . ')';
		return view('base.tx_curr', $data);		
	}


	public function gbv()
	{
		$data = Lookup::view_data_surges();
		$data['ages'] = \App\SurgeAge::gbv()->get();
		$data['modalities'] = \App\SurgeModality::whereIn('modality', ['gbv_sexual', 'gbv_physical'])->get();
		$financial_year = session('filter_financial_year');
		session(['filter_agency' => 1]);
		return view('base.gbv', $data);
	}

	public function violence()
	{
		$data = Lookup::view_data_surges();
		$data['ages'] = \App\SurgeAge::gbv()->get();
		$data['modalities'] = \App\SurgeModality::whereIn('modality', ['gbv_sexual', 'gbv_physical'])->get();
		$financial_year = session('filter_financial_year');
		session(['filter_agency' => 1, 'filter_groupby' => 12]);
		return view('base.violence', $data);
	}

	public function violence_test()
	{
		$data = Lookup::view_data_surges();
		$data['ages'] = \App\SurgeAge::gbv()->get();
		$data['modalities'] = \App\SurgeModality::whereIn('modality', ['gbv_sexual', 'gbv_physical'])->get();
		$financial_year = session('filter_financial_year');
		session(['filter_agency' => 1, 'filter_groupby' => 12]);
		return view('base.violence-test', $data);
	}

	public function hfr()
	{
		$data = Lookup::view_data_surges();
		$data['weeks'] = \App\Week::where('financial_year', '>', 2020)->get();
		$financial_year = session('filter_financial_year');
		session(['filter_agency' => 1, 'filter_groupby' => 12]);
		return view('base.hfr', $data);
	}

	public function hfr_test()
	{
		$data = Lookup::view_data_surges();
		$data['weeks'] = \App\Week::where('financial_year', '>', 2020)->get();
		$financial_year = session('filter_financial_year');
		session(['filter_agency' => 1, 'filter_groupby' => 12]);
		return view('base.hfr_test', $data);
	}
	


	public function indicators()
	{
		$data = Lookup::view_data();
		$data['no_fac'] = true;
		return view('base.indicators', $data);		
	}

	public function regimen()
	{
		$data = Lookup::view_data();
		return view('base.regimen', $data);
	}

	public function guide()
	{
		return view('base.user_guide', ['no_header' => true]);
	}

	public function config()
	{
		return phpinfo();
	}


    public function change_password(Request $request, User $user)
    {
        if(Auth::user()) Auth::logout();
        Auth::login($user);
        session(['session_partner' => $user->partner]);
        
        return view('forms.password_update', ['no_header' => true, 'user' => $user]);
    }



	public function targets()
	{
		$user = auth()->user();
		$partner = session('session_partner');
		$financial_years = Period::selectRaw('distinct financial_year')->get();
		$facilities = \App\ViewFacility::select('id', 'name')->where('partner', $user->partner_id)->get();
		return view('forms.nonmer', ['no_header' => true, 'facilities' => $facilities, 'partner' => $partner, 'financial_years' => $financial_years]);
	}

	public function download_pns()
	{
		$user = auth()->user();
		$partner = session('session_partner');
		$financial_years = Period::selectRaw('distinct financial_year')->where('financial_year', '>=', 2018)->get();
		return view('forms.download_pns', ['no_header' => true, 'partner' => $partner, 'financial_years' => $financial_years]);
	}

	public function set_surge_facilities()
	{
		$user = auth()->user();
		$partner = session('session_partner');
		$facilities = \App\Facility::where('partner', $partner->id)
			->orderBy('is_surge', 'desc')
			->orderBy('name', 'asc')
			->get();
		return view('forms.set_surge_facilities', ['no_header' => true, 'partner' => $partner, 'facilities' => $facilities]);		
	}

	public function download_surge()
	{
		$data = Lookup::view_data_surges();
		$user = auth()->user();
		$data['partner'] = session('session_partner');
		$data['no_header'] = true;
		return view('forms.download_surge', $data);
	}

	public function download_dispensing()
	{
		$data = Lookup::view_data_surges();
		$data['financial_years'] = Period::selectRaw('distinct financial_year')->where('financial_year', '>=', 2019)->get();
		$user = auth()->user();
		$data['partner'] = session('session_partner');
		$data['no_header'] = true;
		return view('forms.download_dispensing', $data);
	}

	public function download_tx_curr()
	{
		$data = Lookup::view_data_surges();
		$user = auth()->user();
		$data['partner'] = session('session_partner');
		$data['no_header'] = true;
		$data['financial_years'] = Period::selectRaw('distinct financial_year')->where('financial_year', '>=', 2019)->get();
		return view('forms.download_tx_curr', $data);
	}

	public function download_weeklies($modality)
	{
		if(!in_array($modality, ['prep_new', 'vmmc_circ'])) abort(404);
		$data = Lookup::view_data_surges();
		$user = auth()->user();
		$data['partner'] = session('session_partner');
		$data['no_header'] = true;
		$data['modality'] = $modality;
		return view('forms.download_weeklies', $data);
	}

	public function download_gbv()
	{
		$data['modalities'] = \App\SurgeModality::where(['tbl_name' => 'd_gender_based_violence'])->get();
		$data['periods'] = \App\Period::where('financial_year', '>', 2019)->get();
		$user = auth()->user();
		$data['partner'] = session('session_partner');
		$data['no_header'] = true;
		return view('forms.download_gbv', $data);
	}

	public function download_cervical_cancer()
	{
		$data['modalities'] = \App\SurgeModality::where(['tbl_name' => 'd_cervical_cancer', 'parent_modality_id' => 0])->get();
		$data['periods'] = \App\Period::where('financial_year', '>', 2020)->get();
		$user = auth()->user();
		$data['partner'] = session('session_partner');
		$data['no_header'] = true;
		return view('forms.download_cervical_cancer', $data);
	}
	public function cervical_cancer_dashboard()
	{
		$data = [];
		return view('base.cervical_dashboard', $data);
	}
	

	public function download_hfr()
	{
		$data['weeks'] = \App\Week::where('financial_year', '>', 2020)->get();
		$user = auth()->user();
		$data['partner'] = session('session_partner');
		$data['no_header'] = true;
		return view('forms.download_hfr_submission', $data);
	}

	public function download_gbv_report()
	{
		$data['financial_years'] = Period::selectRaw('distinct financial_year')->where('financial_year', '>', 2019)->get();
		$data['periods'] = Period::where('financial_year', '>', 2019)->get();
		$data['partners'] = \App\Partner::where(['funding_agency_id' => 1])->get();
		$data['modalities'] = \App\SurgeModality::whereIn('modality', ['gbv_sexual', 'gbv_physical', 'pep_number', 'completed_pep'])->get();
		$data['genders'] = \App\SurgeGender::whereIn('gender', ['male', 'female'])->get();
        $data['ages'] = \App\SurgeAge::gbv()->get();
		$user = auth()->user();
		$data['partner'] = session('session_partner');
		$data['no_header'] = true;
		return view('forms.download_gbv_quarterly_report', $data);
	}

	public function download_hfr_report()
	{
		$data['financial_years'] = Period::selectRaw('distinct financial_year')->where('financial_year', '>', 2020)->get();
		$data['weeks'] = Week::where('financial_year', '>', 2020)->get();
		$data['partners'] = \App\Partner::where(['funding_agency_id' => 1])->get();
		$user = auth()->user();
		$data['partner'] = session('session_partner');
		$data['no_header'] = true;
		return view('forms.download_hfr_quarterly_report', $data);
	}

	public function upload_any($path, $modality=null)
	{
		$user = auth()->user();
		$partner = session('session_partner');
		return view('forms.upload_any', ['no_header' => true, 'partner' => $partner, 'path' => $path, 'modality' => $modality]);	
	}

	public function upload_facilities()
	{
		$user = auth()->user();
		$partner = session('session_partner');
        $partners = \App\Partner::orderBy('name', 'asc')->get();
		return view('forms.upload_facilities', ['no_header' => true, 'partners' => $partners]);
	}
}
