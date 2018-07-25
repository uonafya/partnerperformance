<?php

namespace App;

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
				$value = NULL;
				break;
		}

		return $value;

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
		];
	}

	public static function set_crumb($name = '')
	{
		return "<a href='javascript:void(0)' class='alert-link'><strong>{$name}</strong></a>";
	}

	public static function date_query()
	{
		$default = date('Y');
		$year = session('filter_year', $default);
		$month = session('filter_month');
		$to_year = session('to_year');
		$to_month = session('to_month');

		$query = '';		

		if(!$to_year){
			$query .= " year='{$year}' ";

			if($month) $query .= " month='{$month}' ";
		}
		else{
			$query .= " ((year = '{$year}' AND month >= '{$month}') OR (year = '{$to_year}' AND month <= '{$to_month}') OR (year > '{$year}' AND year > '{$to_year}'))  ";
		}
		return $query;
	}
}
