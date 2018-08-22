<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;
use App\Facility;
use App\ViewFacility;

class IndicatorController extends Controller
{

	public function download_excel($financial_year)
	{
		$partner = session('session_partner');
		$data = [];

		$c = DB::table('view_facilitys')->where('partner', $partner)->groupBy('partner')->get();

	}
}
