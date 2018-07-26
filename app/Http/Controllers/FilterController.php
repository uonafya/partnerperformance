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
		$default = session('filter_year');
		$year = $request->input('year', $default);
		$month = $request->input('month');

		$to_year = $request->input('to_year');
		$to_month = $request->input('to_month');

		session(['filter_year' => $year, 'filter_month' => $month, 'to_year' => $to_year, 'to_month' => $to_month]);

		return ['year' => $year, 'month' => Lookup::resolve_month($month), 'prev_year' => ($year-1)];
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



    public function search(Request $request)
    {
        $search = $request->input('search');
        $facilities = ViewFacility::select('id', 'name', 'facilitycode', 'county')
            ->whereRaw("(name like '%" . $search . "%' OR  facilitycode like '" . $search . "%')")
            ->paginate(10);
        return $facilities;
    }

}
