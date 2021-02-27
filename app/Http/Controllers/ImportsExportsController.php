<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\DispensingImport;
use App\Imports\FacilitiesImport;
use App\Imports\IndicatorImport;
use App\Imports\NonMerImport;
use App\Imports\PNSImport;
use App\Imports\SurgeImport;
use App\Imports\TxCurrentImport;
use App\Imports\WeeklyImport;
use App\Imports\GBVImport;
use App\Imports\CervicalCancerImport;
use App\Imports\TargetsImport;
// use App\Imports\AfyaImport;
use App\Imports\FacilityEditImport;
use App\Imports\HfrSubmissionImport;
use App\Imports\HfrUsaidSubmissionImport;
use App\Imports\CountyTargetsImport;
use App\Imports\CountyGBVTargetsImport;
use App\Imports\FacilityPartnerEditImport;


use App\Exports\DispensingExport;
use App\Exports\IndicatorExport;
use App\Exports\NonMerExport;
use App\Exports\PNSExport;
use App\Exports\SurgeExport;
use App\Exports\TxCurrentExport;
use App\Exports\WeeklyExport;
use App\Exports\GBVExport;
use App\Exports\CervicalCancerExport;
use App\Exports\TargetsExport;
use App\Exports\QuarterlyReportGBV;
use App\Exports\QuarterlyReportHfr;
use App\Exports\QuarterlyHfrSubmissionExport;
use App\Exports\HfrSubmissionExport;
use App\Exports\UsaidGBVExport;

use App\Exports\GenExport;

class ImportsExportsController extends Controller
{

	public function upload_any(Request $request, $path)
	{
		ini_set('memory_limit', '-1');
		if (!$request->hasFile('upload')){
	        session(['toast_error' => 1, 'toast_message' => 'Please select a file before clicking the submit button.']);
			return back();
		}
		if(auth()->user()->user_type_id == 2){
	        session(['toast_error' => 1, 'toast_message' => 'You are not allowed to make an upload.']);
			return back();			
		}
		session()->pull('problem_rows');

		$classes = [
			'dispensing' => DispensingImport::class,
			'facilities' => FacilitiesImport::class,
			'indicators' => IndicatorImport::class,
			'non_mer' => NonMerImport::class,
			'pns' => PNSImport::class,
			'surge' => SurgeImport::class,
			'tx_curr' => TxCurrentImport::class,
			'weekly' => WeeklyImport::class,
			'gbv' => GBVImport::class,
			'cervical-cancer' => CervicalCancerImport::class,
			'targets' => TargetsImport::class,
			// 'afya' => AfyaImport::class,
			'facility-edit' => FacilityEditImport::class,
			'facility-partner-edit' => FacilityPartnerEditImport::class,
			'hfr' => HfrSubmissionImport::class,
			'hfr-usaid' => HfrUsaidSubmissionImport::class,
			'county-target' => CountyTargetsImport::class,
			'county-gbv' => CountyGBVTargetsImport::class,
		];

		$c = $classes[$path];

		$upload = $request->upload->store('uploads');

		if($path == 'facilities'){
			if(auth()->user()->user_type_id != 1) return back();
			Excel::import(new $c($request->input('partner_id')), $upload);
		}
		else if($path == 'weekly'){
			Excel::import(new $c($request->input('modality')), $upload);			
		}
		else{
			Excel::import(new $c, $upload);			
		}

		if(session('problem_rows')) {
			// dd(session('problem_rows'));
			$gen = new GenExport;
			return $gen->csv_download(session()->pull('problem_rows'));
		}

		session(['toast_message' => 'The updates have been made.']);
		return back();
	}

	public function export_non_mer($financial_year)
	{
		ini_set('memory_limit', '-1');
		return new NonMerExport($financial_year);
	}

	public function export_indicator($financial_year)
	{
		ini_set('memory_limit', '-1');
		return new IndicatorExport($financial_year);
	}

	public function export_any(Request $request, $path)
	{
		ini_set('memory_limit', '-1');
		$classes = [
			'dispensing' => DispensingExport::class,
			'non_mer' => NonMerExport::class,
			'pns' => PNSExport::class,
			'surge' => SurgeExport::class,
			'tx_curr' => TxCurrentExport::class,
			'weekly' => WeeklyExport::class,
			'gbv' => GBVExport::class,
			'cervical-cancer' => CervicalCancerExport::class,
			'targets' => TargetsExport::class,
			'quarterly-gbv' => QuarterlyReportGBV::class,
			'quarterly-hfr' => QuarterlyHfrSubmissionExport::class,
			'hfr' => HfrSubmissionExport::class,
			'usaid-gbv' => UsaidGBVExport::class,
		];

		$c = $classes[$path];
		return new $c($request);
	}


	/*
		public function upload_dispensing(Request $request)
		{		
			ini_set('memory_limit', '-1');
			if (!$request->hasFile('upload')){
		        session(['toast_error' => 1, 'toast_message' => 'Please select a file before clicking the submit button.']);
				return back();
			}

			// Excel::import(new DispensingImport, request()->file('upload'));
			Excel::import(new DispensingImport, $request->upload->path());

			session(['toast_message' => 'The updates have been made.']);
			return back();
		}

		public function upload_facilities(Request $request)
		{		
			ini_set('memory_limit', '-1');
			if(auth()->user()->user_type_id != 1) return back();
			if (!$request->hasFile('upload')){
		        session(['toast_error' => 1, 'toast_message' => 'Please select a file before clicking the submit button.']);
				return back();
			}
			Excel::import(new FacilitiesImport($request->input('partner_id')), $request->upload->path());

			session(['toast_message' => 'The updates have been made.']);
			return back();
		}


		public function upload_indicator(Request $request)
		{		
			ini_set('memory_limit', '-1');
			if (!$request->hasFile('upload')){
		        session(['toast_error' => 1, 'toast_message' => 'Please select a file before clicking the submit button.']);
				return back();
			}
			Excel::import(new IndicatorImport, $request->upload->path());

			session(['toast_message' => 'The updates have been made.']);
			return back();
		}


		public function upload_non_mer(Request $request)
		{		
			ini_set('memory_limit', '-1');
			if (!$request->hasFile('upload')){
		        session(['toast_error' => 1, 'toast_message' => 'Please select a file before clicking the submit button.']);
				return back();
			}
			Excel::import(new NonMerImport, $request->upload->path());

			session(['toast_message' => 'The updates have been made.']);
			return back();
		}


		public function upload_pns(Request $request)
		{		
			ini_set('memory_limit', '-1');
			if (!$request->hasFile('upload')){
		        session(['toast_error' => 1, 'toast_message' => 'Please select a file before clicking the submit button.']);
				return back();
			}

			Excel::import(new PNSImport, $request->upload->path());

			session(['toast_message' => 'The updates have been made.']);
			return back();
		}


		public function upload_surge(Request $request)
		{		
			ini_set('memory_limit', '-1');
			if (!$request->hasFile('upload')){
		        session(['toast_error' => 1, 'toast_message' => 'Please select a file before clicking the submit button.']);
				return back();
			}

			Excel::import(new SurgeImport, $request->upload->path());

			session(['toast_message' => 'The updates have been made.']);
			return back();
		}


		public function upload_tx_current(Request $request)
		{		
			ini_set('memory_limit', '-1');
			if (!$request->hasFile('upload')){
		        session(['toast_error' => 1, 'toast_message' => 'Please select a file before clicking the submit button.']);
				return back();
			}

			Excel::import(new TxCurrentImport, $request->upload->path());

			session(['toast_message' => 'The updates have been made.']);
			return back();
		}
	*/


}
