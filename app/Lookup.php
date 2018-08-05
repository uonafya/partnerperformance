<?php

namespace App;

use \App\Division;

use \App\County;
use \App\Subcounty;
use \App\Partner;
use \App\Ward;
use \App\Facility;

use Illuminate\Support\Facades\Cache;

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
		$partners = Partner::select('id', 'name')->where('flag', 1)->orderBy('name', 'asc')->get();
		$counties = County::select('id', 'name')->orderBy('name', 'asc')->get();
		$subcounties = Subcounty::select('id', 'name')->orderBy('name', 'asc')->get();
		$wards = Ward::select('id', 'name')->orderBy('name', 'asc')->get();
		$divisions = Division::all();

		return [
			'divisions' => $divisions,
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

	public static function divisions_query()
	{
		$query = "1 ";
		if(session('filter_county')) $query .= " AND county='" . session('filter_county') . "' ";
		if(session('filter_subcounty')) $query .= " AND subcounty_id='" . session('filter_subcounty') . "' ";
		if(session('filter_ward')) $query .= " AND ward_id='" . session('filter_ward') . "' ";
		if(session('filter_facility')) $query .= " AND id='" . session('filter_facility') . "' ";
		if(session('filter_partner')) $query .= " AND partner='" . session('filter_partner') . "' ";

		return $query;
	}
}
