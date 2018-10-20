<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Lookup;

use \App\County;
use \App\Subcounty;
use \App\Partner;
use \App\Ward;
use \App\Facility;
use \App\ViewFacility;

class FilterController extends Controller
{
	public function filter_date(Request $request)
	{
		$default_financial = session('filter_financial_year');

		$year = $request->input('year');
		$month = $request->input('month');

		$to_year = $request->input('to_year');
		$to_month = $request->input('to_month');
		$prev_year = ($year - 1);

		$financial_year = $request->input('financial_year', $default_financial);
		$quarter = $request->input('quarter');

		$range = ['filter_year' => $year, 'filter_month' => $month, 'to_year' => $to_year, 'to_month' => $to_month, 'filter_financial_year' => $financial_year, 'filter_quarter' => $quarter];

		session($range);

		$display_date = ' (October, ' . ($financial_year-1) . ' - September ' . $financial_year . ')';
		if($quarter){
			switch ($quarter) {
				case 1:
					$display_date = "(October - December " . ($financial_year-1) . ")";
					break;
				case 2:
					$display_date = "(January - March " . $financial_year . ")";
					break;
				case 3:
					$display_date = "(April - June " . $financial_year . ")";
					break;
				case 4:
					$display_date = "(July - September " . $financial_year . ")";
					break;					
				default:
					break;
			}
		}
		if($month){
			if($month < 10) $display_date = '(' . $financial_year . ' ' . Lookup::resolve_month($month) . ')';
			if($month > 9) $display_date = '(' . ($financial_year-1) . ' ' . Lookup::resolve_month($month) . ')';
		}
		if($to_year){
			if($year == $to_year) 
				$display_date = '(' . Lookup::resolve_month($month) . ' - ' . Lookup::resolve_month($to_month) . " {$year})";
			else{
				$display_date = "(" . Lookup::resolve_month($month) . ", {$year} - " . Lookup::resolve_month($to_month) . ", {$to_year})";
			}
		}

		return ['year' => $year, 'prev_year' => $prev_year, 'range' => $range, 'display_date' => $display_date];
	}


	public function filter_any(Request $request)
	{
		$var = $request->input('session_var');
		$val = $request->input('value');

		if($val == null || (!is_array($val) && !is_numeric($val)) || (is_array($val) && in_array('null', $val)) ) $val = null;
		session([$var => $val]);

		return [$var => $val];
	}

    public function facility(Request $request)
    {
        $search = $request->input('search');
        $facilities = Facility::select('id', 'name', 'facilitycode')
            ->whereRaw("(name like '%" . $search . "%' OR  facilitycode like '" . $search . "%')")
            ->where('flag', 1)
            ->paginate(10);
        return $facilities;
    }


	public function get_current_header()
	{
    	$year = ((int) Date('Y'));
    	$prev_year = ((int) Date('Y')) - 1;
    	$month = ((int) Date('m')) - 1;
    	$prev_month = ((int) Date('m'));

    	if($month == 0){
    		return "(Jan - Dec {$prev_year})";
    	}
    	else{
    		return "(" . Lookup::resolve_month($prev_month) . ", {$prev_year} - " . Lookup::resolve_month($month) . ", {$year})";
    	}
	}
}
