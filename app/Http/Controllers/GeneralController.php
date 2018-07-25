<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;

class GeneralController extends Controller
{

	public function partner_home()
	{
		$data = Lookup::partner_data();
		return view('partners.home', $data);
	}
}
