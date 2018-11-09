<?php

namespace App\Http\Controllers\Former;

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
		$default = session('filter_year');
		$default_financial = session('filter_financial_year');

		$year = $request->input('year', $default);
		$month = $request->input('month');

		$to_year = $request->input('to_year');
		$to_month = $request->input('to_month');
		$prev_year = ($year - 1);

		$financial_year = $request->input('financial_year', $default_financial);
		$quarter = $request->input('quarter');

		$range = ['filter_year' => $year, 'filter_month' => $month, 'to_year' => $to_year, 'to_month' => $to_month, 'filter_financial_year' => $financial_year, 'filter_quarter' => $quarter];

		session($range);

		if(session('financial')){
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
		}else{
			$display_date = $year . ' ' . Lookup::resolve_month($month);
		}		

		return ['year' => $year, 'prev_year' => $prev_year, 'range' => $range, 'display_date' => $display_date];
	}

	public function filter_partner(Request $request)
	{
		$partner = $request->input('partner');
		if($partner == null || !is_numeric($partner)) $partner = null;

		session(['filter_partner' => $partner]);

		$name = "All Partners";

		if($partner || $partner == 0) $name = Partner::find($partner)->name ?? '';
		$crumb = Lookup::set_crumb($name);

		return  ['partner' => $partner, 'crumb' => $crumb];
	}

	public function filter_any(Request $request)
	{
		$var = $request->input('session_var');
		$val = $request->input('value');

		if($val == null || !is_numeric($val)) $val = null;
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
