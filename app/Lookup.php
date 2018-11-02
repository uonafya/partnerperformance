<?php

namespace App;

use \App\Division;

use \App\County;
use \App\Subcounty;
use \App\Partner;
use \App\Ward;
use \App\Facility;

use Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

use App\Mail\Duplicate;

class Lookup
{

	public static function resolve_month($month)
	{
		switch ($month) {
			case 1:
				$value = 'Jan';
				break;
			case 2:
				$value = 'Feb';
				break;
			case 3:
				$value = 'Mar';
				break;
			case 4:
				$value = 'Apr';
				break;
			case 5:
				$value = 'May';
				break;
			case 6:
				$value = 'Jun';
				break;
			case 7:
				$value = 'Jul';
				break;
			case 8:
				$value = 'Aug';
				break;
			case 9:
				$value = 'Sep';
				break;
			case 10:
				$value = 'Oct';
				break;
			case 11:
				$value = 'Nov';
				break;
			case 12:
				$value = 'Dec';
				break;
			default:
				$value = '';
				break;
		}

		return $value;

	}

	public static function get_category($row)
	{
		$groupby = session('filter_groupby', 1);
		if($groupby > 9){
			if($groupby == 10) return 'Calendar Year ' . $row->year;
			if($groupby == 11) return 'FY ' . $row->financial_year;
			if($groupby == 12) return self::resolve_month($row->month) . ', ' . $row->year;
			if($groupby == 13) return "FY {$row->financial_year} Q {$row->quarter}";
		}
		else{
			return $row->name;
		}	
	} 

	public static function get_month_category($year, $month)
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

	public static function get_target_divisor()
	{
		$groupby = session('filter_groupby', 1);
		if($groupby > 9){
			if($groupby == 10 || $groupby == 11) return 1;
			if($groupby == 12) return 12;
			if($groupby == 13) return 4;
		}
		else{			
			$financial_year = session('filter_financial_year');
			$quarter = session('filter_quarter');

			$year = session('filter_year');
			$month = session('filter_month');
			$to_year = session('to_year');
			$to_month = session('to_month');

			if($quarter) return 4;
			if($to_year){
				$first = Carbon::create($year, $month, 1);
				$second = Carbon::create($to_year, $to_month, 1);
				$months = $second->diffInMonths($first);
				return (12 / $months);
			}
			if($month) return 12;
			return 1;
		}
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
			'date_url' => url('filter/date'),
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
			'date_url' => url('filter/date'),
		];
	}

	public static function table_data()
	{
		$data['div'] = str_random(15);
		$data['groupby'] = session('filter_groupby', 1);
		$data['i'] = 0;

		$data['calc_percentage'] = function($num, $den, $roundby=2)
			{
				if(!$den){
					$val = null;
				}else{
					$val = round(($num / $den * 100), $roundby) . "%";
				}
				return $val;
			};

		$data['get_val'] = function($groupby, $row, $collection, $attribute, $number_format=false)
		{
			if($groupby > 9){
				if($groupby == 10) $match = $collection->where('year', $row->year)->first();
				if($groupby == 11) $match = $collection->where('financial_year', $row->financial_year)->first();
				if($groupby == 12) $match = $collection->where('year', $row->year)->where('month', $row->month)->first();
				if($groupby == 13) $match = $collection->where('financial_year', $row->financial_year)->where('quarter', $row->quarter)->first();
			}
			else{
				$match = $collection->where('div_id', $row->div_id)->first();
			}
			if($match){
				if(is_array($attribute)){
					$data = [];
					foreach ($attribute as $key => $value) {
						$data[$value] = $match->$value ?? null;
					}
					return $data;
				}
				else{
					$val = $match->$attribute ?? null;
					if($number_format) return number_format($val);
					return $val;					
				}
			}
			return null;
		};
		return $data;
	}

	public static function get_val($row, $collection, $attribute, $number_format=false)
	{
		$groupby = session('filter_groupby', 1);
		if($groupby > 9){
			if($groupby == 10) $match = $collection->where('year', $row->year)->first();
			if($groupby == 11) $match = $collection->where('financial_year', $row->financial_year)->first();
			if($groupby == 12) $match = $collection->where('year', $row->year)->where('month', $row->month)->first();
			if($groupby == 13) $match = $collection->where('financial_year', $row->financial_year)->where('quarter', $row->quarter)->first();
		}
		else{
			$match = $collection->where('div_id', $row->div_id)->first();
		}
		if($match){
			if(is_array($attribute)){
				$data = [];
				foreach ($attribute as $key => $value) {
					$data[$value] = $match->$value ?? null;
				}
				return $data;
			}
			else{
				$val = $match->$attribute ?? null;
				if($number_format) return number_format($val);
				return $val;				
			}
		}
		return null;		
	}

	public static function set_crumb($name = '')
	{
		return "<a href='javascript:void(0)' class='alert-link'><center><strong>{$name}</strong></center></a>";
	}

	public static function date_query($for_target=false)
	{
		$financial_year = session('filter_financial_year');
		$quarter = session('filter_quarter');

		$year = session('filter_year');
		$month = session('filter_month');
		$to_year = session('to_year');
		$to_month = session('to_month');

		if($for_target) return " financial_year='{$financial_year}'";

		if($to_year) return self::date_range_query($year, $to_year, $month, $to_month);

		$query = " financial_year='{$financial_year}'";
		if($quarter) $query .= " AND quarter='{$quarter}'";
		if($month) $query .= " AND month='{$month}'";

		return $query;

		// if(session('financial') || $for_target){
		// 	$financial_year = session('filter_financial_year');
		// 	$quarter = session('filter_quarter');
		// 	$query = " financial_year='{$financial_year}'";

		// 	if($quarter && !$for_target) $query .= " AND quarter='{$quarter}'";
		// }else{
		// 	$default = date('Y');
		// 	$year = session('filter_year', $default);
		// 	$month = session('filter_month');
		// 	$to_year = session('to_year');
		// 	$to_month = session('to_month');

		// 	$query = '';		

		// 	if(!$to_year){
		// 		$query .= " year='{$year}' ";

		// 		if($month) $query .= " AND month='{$month}' ";
		// 	}
		// 	else{
		// 		$query = self::date_range_query($year, $to_year, $month, $to_month);
		// 	}
		// }
		// return $query;
	}

	public static function apidb_date_query_old($for_target=false)
	{
		if(session('financial') || $for_target){
			$financial_year = session('filter_financial_year');
			$quarter = session('filter_quarter');

			$prev_year = $financial_year-1;

			if($quarter){
				$month = self::min_per_quarter($quarter);
				$to_month = self::max_per_quarter($quarter);
				if($quarter == 1) $query = self::date_range_query($prev_year, $prev_year, $month, $to_month);				
				else{
					$query = self::date_range_query($financial_year, $financial_year, $month, $to_month);
				}
			}
			else{
				$query = self::date_range_query($prev_year, $financial_year, 10, 9);
			}

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
				$query = self::date_range_query($year, $to_year, $month, $to_month);
			}
		}
		return $query;
	}

	public static function apidb_date_query($for_target=false)
	{
		$financial_year = session('filter_financial_year');
		$quarter = session('filter_quarter');

		$year = session('filter_year');
		$month = session('filter_month');
		$to_year = session('to_year');
		$to_month = session('to_month');

		if($to_year) return self::date_range_query($year, $to_year, $month, $to_month);

		$prev_year = $financial_year-1;

		if($quarter){
			$month = self::min_per_quarter($quarter);
			$to_month = self::max_per_quarter($quarter);
			if($quarter == 1) $query = self::date_range_query($prev_year, $prev_year, $month, $to_month);				
			else{
				$query = self::date_range_query($financial_year, $financial_year, $month, $to_month);
			}
		}
		else if($month){
			if($month > 9) $query = "year={$prev_year} ";
			if($month < 10) $query = "year={$financial_year} ";
			$query .= " and month={$month} ";
		}
		else{
			$query = self::date_range_query($prev_year, $financial_year, 10, 9);
		}

		return $query;
	}

	public static function date_range_query($year, $to_year, $month, $to_month)
	{
		if($year == $to_year) return " year={$year} AND month between {$month} and {$to_month} ";
		return " ((year = '{$year}' AND month >= '{$month}') OR (year = '{$to_year}' AND month <= '{$to_month}') OR (year > '{$year}' AND year < '{$to_year}')) ";
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

	public static function year_month_query($deduction=2)
	{
		if(session('financial')){
			$cfy = date('Y');
			if(date('m') > 9) $cfy++;

			$financial_year = session('filter_financial_year');
			$quarter = session('filter_quarter');
			$m = session('filter_month');

			if(!$quarter){
				// if($financial_year <> $cfy) return " financial_year='{$financial_year}' and month=9";
				if($financial_year <> $cfy){
					$month = 9 - ($deduction-1);
					if($m) $month = $m;
				}
				else{
					$month = date('m') - $deduction;
					// if(date('d') < 10) $month--;
					// if($month == 9) $financial_year--;
					if($month < 10 && date('m') > 9) $financial_year--;
					if($month < 1) $month += 12;
					if($m) $month = $m;
					// return " financial_year='{$financial_year}' and month='{$month}'";
				}
			}
			else{
				$n = \App\Synch::get_financial_year_quarter(date('Y'), date('m'));
				$month = self::max_per_quarter($quarter) - ($deduction-1);

				if($financial_year <> $cfy || ($financial_year == $cfy && $quarter <> $n['quarter'])){					
					// return " financial_year='{$financial_year}' and month='{$month}'";
				}
				else{
					$month = date('m') - $deduction;
					// if(date('d') < 10) $month--;
					// if($month == 9) $financial_year--;
					if($month < 10 && date('m') > 9) $financial_year--;
					if($month < 1) $month += 12;
					// return " financial_year='{$financial_year}' and month='{$month}'";
				}
			}
		}
		session(['tx_financial_year' => $financial_year, 'tx_month' => $month]);
		return " financial_year='{$financial_year}' and month='{$month}'";
	}

	public static function year_month_name()
	{
		return '(' . session('tx_financial_year') . ', ' . Lookup::resolve_month(session('tx_month')) . ')';
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

	public static function min_per_quarter($quarter){
		switch ($quarter) {
			case 1:
				$m = 10;
				break;
			case 2:
				$m = 1;
				break;
			case 3:
				$m = 4;
				break;
			case 4:
				$m = 7;
				break;			
			default:
				break;
		}
		return $m;
	}

	/*public static function divisions_query()
	{
		$query = " 1 ";
		if(session('filter_county')) $query .= " AND county=" . session('filter_county') . " ";
		if(session('filter_subcounty')) $query .= " AND subcounty_id=" . session('filter_subcounty') . " ";
		if(session('filter_ward')) $query .= " AND ward_id=" . session('filter_ward') . " ";
		if(session('filter_facility')) $query .= " AND view_facilitys.id=" . session('filter_facility') . " ";
		if(session('filter_partner')) $query .= " AND partner=" . session('filter_partner') . " ";
		if(session('filter_agency')) $query .= " AND funding_agency_id=" . session('filter_agency') . " ";

		return $query;
	}*/

	public static function divisions_query()
	{
		$query = " 1 ";
		if(session('filter_county')) $query .= " AND county" . self::set_division_query(session('filter_county'));
		if(session('filter_subcounty')) $query .= " AND subcounty_id" . self::set_division_query(session('filter_subcounty'));
		if(session('filter_ward')) $query .= " AND ward_id" . self::set_division_query(session('filter_ward'));
		if(session('filter_facility')) $query .= " AND view_facilitys.id" . self::set_division_query(session('filter_facility'));
		if(session('filter_partner') || is_numeric(session('filter_partner'))) $query .= " AND partner" . self::set_division_query(session('filter_partner'));
		if(session('filter_agency')) $query .= " AND funding_agency_id" . self::set_division_query(session('filter_agency'));

		return $query;
	}

	public static function set_division_query($param, $quote=false)
	{
		if(is_array($param)){
			$str = " IN (";
			foreach ($param as $key => $value) {
				if($quote) $str .= "'{$value}', ";
				else{
					$str .= "{$value}, ";
				}				
			}
			$str = substr($str, 0, -2);
			$str .= ") ";
			return $str;
		}
		else{
			if($quote) return "='{$param}' ";
			return "={$param} ";
		}
	}

	public static function groupby_query($def=true)
	{
		$groupby = session('filter_groupby', 1);

		switch ($groupby) {
			case 1:
				$select_query = "partner as div_id";
				if($def) $select_query .= ", partnername as name";
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
				$select_query = "funding_agency_id as div_id";
				if($def) $select_query .= ", funding_agency as name";
				$group_query = "funding_agency_id";
				break;
			case 10:
				$select_query = "year";
				$group_query = "year";
				break;
			case 11:
				$select_query = "financial_year";
				$group_query = "financial_year";
				break;	
			case 12:
				$select_query = "year, month";
				$group_query = "year, month";
				break;	
			case 13:
				$select_query = "financial_year, quarter";
				$group_query = "financial_year, quarter";
				break;			
			default:
				break;
		}
		return ['select_query' => $select_query, 'group_query' => $group_query];
	}

	public static function duplicate_parameters($row)
	{
		$groupby = session('filter_groupby', 1);

		if($groupby == 12) return ['year', $row->year, 'month', $row->month];
		else if($groupby == 13) return ['financial_year', $row->financial_year, 'quarter', $row->quarter];
		else{
			$d = [];
			$q = self::groupby_query();
			if($groupby < 10) $d = [$q['group_query'], $row->div_id];
			else{
				$col = $q['group_query'];
				$d = [$col, $row->$col];
			}
			return array_merge($d, ['', '']);
		}
	}

	public static function groupby_query_indicators()
	{
		$groupby = session('filter_groupby', 1);

		switch ($groupby) {
			case 1:
				$select_query = "partner as div_id, partners.name";
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
				$select_query = "funding_agency_id as div_id";
				if($def) $select_query .= ", funding_agency as name";
				$group_query = "funding_agency_id";
				break;
			case 10:
				$select_query = "year";
				$group_query = "year";
				break;
			case 11:
				$select_query = "financial_year";
				$group_query = "financial_year";
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

	public static function get_current_header()
	{		
    	$year = ((int) Date('Y'));
    	$prev_year = ((int) Date('Y')) - 1;
    	$month = ((int) Date('m')) - 1;
    	$prev_month = ((int) Date('m'));

    	if($month == 0){
    		return "(Jan - Dec {$prev_year})";
    	}
    	else{
    		return "(" . self::resolve_month($prev_month) . ", {$prev_year} - " . self::resolve_month($month) . ", {$year})";
    	}
	}
}
