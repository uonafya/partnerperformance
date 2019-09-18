<?php

namespace App;

class PNS
{

	public $item_array = [
		'screened' => 'Index Clients Screened',
		'contacts_identified' => 'Contacts Identified',
		'pos_contacts' => 'Known HIV Positive Contacts',
		'eligible_contacts' => 'Eligible Contacts',
		'contacts_tested' => 'Contacts Tested',
		'new_pos' => 'Newly Identified Positives',
		'linked_haart' => 'Linked To HAART',
	];

	public $ages_array = [
		'unknown_m' => 'Unknown Male',
		'unknown_f' => 'Unknown Female',
		'below_1' => 'Below 1',
		'below_10' => '1-9',
		'below_15_m' => '10-14 Male',
		'below_15_f' => '10-14 Female',
		'below_20_m' => '15-19 Male',
		'below_20_f' => '15-19 Female',
		'below_25_m' => '20-24 Male',
		'below_25_f' => '20-24 Female',
		'below_30_m' => '25-29 Male',
		'below_30_f' => '25-29 Female',
		'below_50_m' => '30-49 Male',
		'below_50_f' => '30-49 Female',
		'above_50_m' => 'Above 50 Male',
		'above_50_f' => 'Above 50 Female',
	];

	public $male_array = ['below_15_m', 'below_20_m', 'below_25_m', 'below_30_m', 'below_50_m', 'above_50_m'];
	public $female_array = ['below_15_f', 'below_20_f', 'below_25_f', 'below_30_f', 'below_50_f', 'above_50_f'];

	public $mf_array = ['unknown_m', 'unknown_f', 'below_1', 'below_10', 'below_15_m', 'below_20_m', 'below_25_m', 'below_30_m', 'below_50_m', 'above_50_m',
	'below_15_f', 'below_20_f', 'below_25_f', 'below_30_f', 'below_50_f', 'above_50_f'];

}
