<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Lookup;

use App\SurgeAge;
use App\SurgeGender;
use App\SurgeModality;
use App\SurgeColumn;
use App\SurgeColumnView;
// use App\Surge;
// use App\Surge;
// use App\Surge;

class SurgeController extends Controller
{


	public function download_excel(Request $request)
	{
		$partner = session('session_partner');
		if(!$partner){
			$partner = auth()->user()->partner;
			session(['session_partner' => $partner]);
		}
		$data = [];

		$week = $request->input('week');
		$modalities = $request->input('modalities');
		$gender = $request->input('gender');

	}
}
