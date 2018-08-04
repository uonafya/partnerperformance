<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;

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
}
