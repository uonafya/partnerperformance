<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lookup;
use Excel;

class GeneralController extends Controller
{

	public function partner_home()
	{
		session(['financial' => false]);
		$data = Lookup::partner_data();
		return view('partners.home', $data);
	}

	public function home()
	{
		$data = Lookup::view_data();
		return view('base.home', $data);
	}

	public function current()
	{
		ini_set("memory_limit", "-1");
		$path = public_path('targets/new_2018.xlsx');
		$data = Excel::load($path, function($reader){

		})->get();
		dd($data);
	}
}
