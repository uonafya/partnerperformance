<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lookup;
use App\User;
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
		$data = Lookup::view_data();
		return view('base.dupli_home', $data);
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

	public function otz()
	{
		$data = Lookup::view_data();
		return view('base.otz', $data);
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
		$facilities = \App\ViewFacility::select('id', 'name')->where('partner', $user->partner_id)->get();
		return view('forms.nonmer', ['no_header' => true, 'facilities' => $facilities, 'partner' => $partner]);
	}

	public function download_pns()
	{
		$user = auth()->user();
		$partner = session('session_partner');
		return view('forms.download_pns', ['no_header' => true, 'partner' => $partner]);
	}

	public function upload_pns()
	{
		$user = auth()->user();
		$partner = session('session_partner');
		return view('forms.upload_pns', ['no_header' => true, 'partner' => $partner]);
	}

	public function upload_nonmer()
	{
		$user = auth()->user();
		$partner = session('session_partner');
		return view('forms.upload_nonmer', ['no_header' => true, 'partner' => $partner]);
	}

	public function upload_indicators()
	{
		$user = auth()->user();
		$partner = session('session_partner');
		return view('forms.upload_indicators', ['no_header' => true, 'partner' => $partner]);
	}
}
