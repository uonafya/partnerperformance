<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;
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

	public function guide()
	{
		return view('base.user_guide', ['no_header' => true]);
	}


	public function targets()
	{
		$user = auth()->user();
		$partner = session('session_partner');
		$facilities = \App\ViewFacility::select('id', 'name')->where('partner', $user->partner_id)->get();
		return view('base.targets', ['no_header' => true, 'facilities' => $facilities, 'partner' => $partner]);
	}
}
