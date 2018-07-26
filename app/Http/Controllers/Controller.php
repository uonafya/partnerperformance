<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Lookup;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    public function pre_partners()
    {    	
		$partner = session('filter_partner');
		$where = null;
		$date_query = Lookup::date_query();

		// For a specific partner
		if($partner && is_numeric($partner)){
			$sql = " name, facilitycode, dhiscode, ";
			$division = "facility";
			$groupBy = "view_facilitys.id";
			$where = ['partner' => $partner];
		}
		// For all partners
		else{
			$sql = " partner, partnername, ";
			$division = "partner";
			$groupBy = "view_facilitys.partner";
		}

		return ['sql' => $sql, 'where' => $where, 'division' => $division, 'groupBy' => $groupBy];

    }
}
