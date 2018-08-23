<?php

namespace App;

use \App\Division;

use \App\County;
use \App\Subcounty;
use \App\Partner;
use \App\Ward;
use \App\Facility;

use Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

use App\Mail\Duplicate;

class Lookup
{

	public static function resolve_month($month)
	{
		switch ($month) {
			case 1:
				$value = 'January';
				break;
			case 2:
				$value = 'February';
				break;
			case 3:
				$value = 'March';
				break;
			case 4:
				$value = 'April';
				break;
			case 5:
				$value = 'May';
				break;
			case 6:
				$value = 'June';
				break;
			case 7:
				$value = 'July';
				break;
			case 8:
				$value = 'August';
				break;
			case 9:
				$value = 'September';
				break;
			case 10:
				$value = 'October';
				break;
			case 11:
				$value = 'November';
				break;
			case 12:
				$value = 'December';
				break;
			default:
				$value = '';
				break;
		}

		return $value;

	}

	public static function get_category($year, $month)
	{
		$m = self::resolve_month($month);
		return substr($m, 0, 3) . ', ' . $year;
	}

	public static function get_percentage($num, $den, $roundby=2)
	{
		if(!$den){
			$val = 0;
		}else{
			$val = round(($num / $den * 100), $roundby);
		}
		return $val;
	}

	public static function progress_status($val)
	{
		// Bootstrap 4
		/*if($val > 99) $status = 'bg-success';
		else if($val > 80) $status = 'bg-info';
		else if($val > 50) $status = 'bg-warning';
		else if($val <= 50) $status = 'bg-danger';
		else{
			$status = '';
		}*/

		if($val > 99) $status = 'progress-bar-success';
		else if($val > 80) $status = 'progress-bar-info';
		else if($val > 50) $status = 'progress-bar-warning';
		else if($val <= 50) $status = 'progress-bar-danger';
		else{
			$status = '';
		}
		return $status;
	}

	public static function partner_data()
	{
		// $default_breadcrumb = "<a href='javascript:void(0)' class='alert-link'><strong>All Partners</strong></a>";
		$default_breadcrumb = self::set_crumb('All Partners');

		$partners = Partner::select('id', 'name')->where('flag', 1)->orderBy('name', 'asc')->get();
		$select_options = "<option disabled='true' selected='true'> Select Partner: <option/> ";
		$select_options .= "<option value='null' selected='true'> All Partners <option/> ";

		foreach ($partners as $partner) {
			$select_options .= "<option value='{$partner->id}'> {$partner->name} <option/>";
		}

		return [
			'default_breadcrumb' => $default_breadcrumb,
			'select_options' => $select_options,
			'date_url' => secure_url('filter/date'),
		];
	}

	public static function view_data()
	{
		$divisions = Division::all();
		$agencies = FundingAgency::all();

		$partners = Partner::select('id', 'name')->where('flag', 1)->orderBy('name', 'asc')->get();
		$counties = County::select('id', 'name')->orderBy('name', 'asc')->get();
		$subcounties = Subcounty::select('id', 'name')->orderBy('name', 'asc')->get();
		$wards = Ward::select('id', 'name')->orderBy('name', 'asc')->get();

		return [
			'divisions' => $divisions,
			'agencies' => $agencies,
			'partners' => $partners,
			'counties' => $counties,
			'subcounties' => $subcounties,
			'wards' => $wards,
			'date_url' => secure_url('filter/date'),
		];
	}

	public static function set_crumb($name = '')
	{
		return "<a href='javascript:void(0)' class='alert-link'><center><strong>{$name}</strong></center></a>";
	}

	public static function date_query($for_target=false)
	{
		if(session('financial') || $for_target){
			$financial_year = session('filter_financial_year');
			$quarter = session('filter_quarter');
			$query = " financial_year='{$financial_year}'";

			if($quarter && !$for_target) $query .= " AND quarter='{$quarter}'";
		}else{
			$default = date('Y');
			$year = session('filter_year', $default);
			$month = session('filter_month');
			$to_year = session('to_year');
			$to_month = session('to_month');

			$query = '';		

			if(!$to_year){
				$query .= " year='{$year}' ";

				if($month) $query .= " AND month='{$month}' ";
			}
			else{
				$query .= " ((year = '{$year}' AND month >= '{$month}') OR (year = '{$to_year}' AND month <= '{$to_month}') OR (year > '{$year}' AND year > '{$to_year}'))  ";
			}
		}
		return $query;
	}

	/*public static function year_month_query()
	{
		if(session('financial')){
			$cfy = date('Y');
			if(date('m') > 9) $cfy++;

			$financial_year = session('filter_financial_year');
			$quarter = session('filter_quarter');
			$m = session('filter_month');

			if(!$quarter){
				if($financial_year <> $cfy) return " financial_year='{$financial_year}' and month=9";
				else{
					$month = date('m') - 1;
					if(date('d') < 10) $month--;
					if($month == 9) $financial_year--;
					if($month < 1) $month += 12;
					if($m) $month = $m;
					return " financial_year='{$financial_year}' and month='{$month}'";
				}
			}
			else{
				$n = \App\Synch::get_financial_year_quarter(date('Y'), date('m'));
				$month = self::max_per_quarter($quarter);

				if($financial_year <> $cfy || ($financial_year == $cfy && $quarter <> $n['quarter'])){					
					return " financial_year='{$financial_year}' and month='{$month}'";
				}
				else{
					$month = date('m') - 1;
					if(date('d') < 10) $month--;
					if($month == 9) $financial_year--;
					if($month < 1) $month += 12;
					return " financial_year='{$financial_year}' and month='{$month}'";
				}
			}
		}
	}*/

	public static function year_month_query()
	{
		if(session('financial')){
			$cfy = date('Y');
			if(date('m') > 9) $cfy++;

			$financial_year = session('filter_financial_year');
			$quarter = session('filter_quarter');
			$m = session('filter_month');

			if(!$quarter){
				if($financial_year <> $cfy) return " financial_year='{$financial_year}' and month=9";
				else{
					$month = date('m') - 2;
					// if(date('d') < 10) $month--;
					if($month == 9) $financial_year--;
					if($month < 1) $month += 12;
					if($m) $month = $m;
					return " financial_year='{$financial_year}' and month='{$month}'";
				}
			}
			else{
				$n = \App\Synch::get_financial_year_quarter(date('Y'), date('m'));
				$month = self::max_per_quarter($quarter);

				if($financial_year <> $cfy || ($financial_year == $cfy && $quarter <> $n['quarter'])){					
					return " financial_year='{$financial_year}' and month='{$month}'";
				}
				else{
					$month = date('m') - 2;
					// if(date('d') < 10) $month--;
					if($month == 9) $financial_year--;
					if($month < 1) $month += 12;
					return " financial_year='{$financial_year}' and month='{$month}'";
				}
			}
		}
	}

	public static function max_per_quarter($quarter){
		switch ($quarter) {
			case 1:
				$m = 12;
				break;
			case 2:
				$m = 3;
				break;
			case 3:
				$m = 6;
				break;
			case 4:
				$m = 9;
				break;			
			default:
				break;
		}
		return $m;
	}

	public static function divisions_query()
	{
		$query = "1 ";
		if(session('filter_county')) $query .= " AND county='" . session('filter_county') . "' ";
		if(session('filter_subcounty')) $query .= " AND subcounty_id='" . session('filter_subcounty') . "' ";
		if(session('filter_ward')) $query .= " AND ward_id='" . session('filter_ward') . "' ";
		if(session('filter_facility')) $query .= " AND view_facilitys.id='" . session('filter_facility') . "' ";
		if(session('filter_partner')) $query .= " AND partner='" . session('filter_partner') . "' ";
		if(session('filter_agency')) $query .= " AND funding_agency_id='" . session('filter_agency') . "' ";

		return $query;
	}

	public static function groupby_query()
	{
		$groupby = session('filter_groupby', 1);

		switch ($groupby) {
			case 1:
				$select_query = "partner as div_id, partnername as name";
				$group_query = "partner";
				break;
			case 2:
				$select_query = "county as div_id, countyname as name, CountyDHISCode as dhis_code, CountyMFLCode as mfl_code";
				$group_query = "county";
				break;
			case 3:
				$select_query = "subcounty_id as div_id, subcounty as name, SubCountyDHISCode as dhis_code, SubCountyMFLCode as mfl_code";
				$group_query = "subcounty_id";
				break;
			case 4:
				$select_query = "ward_id as div_id, wardname as name, WardDHISCode as dhis_code, WardMFLCode as mfl_code";
				$group_query = "ward_id";
				break;
			case 5:
				$select_query = "view_facilitys.id as div_id, name, new_name, DHIScode as dhis_code, facilitycode as mfl_code";
				$group_query = "view_facilitys.id";
				break;
			case 6:
				$select_query = "funding_agency_id as div_id, funding_agency";
				$group_query = "funding_agency_id";
				break;			
			default:
				break;
		}
		return ['select_query' => $select_query, 'group_query' => $group_query];
	}

    public static function send_report(){
    	$mail_array = ['joelkith@gmail.com', 'tngugi@gmail.com', 'baksajoshua09@gmail.com'];
    	Mail::to($mail_array)->send(new Duplicate());
    }

    public static function print_duplicates($duplicates, $filename='duplicates')
    {
    	Excel::create('duplicates', function($excel) use($duplicates){
    		$excel->sheet('sheet1', function($sheet) use($duplicates){
    			$sheet->fromArray($duplicates);
    		});
    	})->store('csv');
    }

    public static function clean_zero($val)
    {    	
    	if(is_numeric($val)) return $val;
    	return null;
    	// if($val == '' || $val == '0' || !$val) return null;
    	// return $val;
    }

    public static function clean_boolean($val)
    {
    	$val = strtolower($val);

    	$pos = strpos($val, 'y');
    	if(is_numeric($pos)) return 1;
    	$pos = strpos($val, 'n');
    	if(is_numeric($pos)) return 0;

    	return null;
    }

    public static function get_boolean($val)
    {
    	if($val == 1) return 'YES';
    	// if($val == 0) return 'NO';
    	return null;
    }
}
