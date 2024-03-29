<?php

namespace App;

use DB;
use App\Synch;
use App\Period;
use App\User;
use Illuminate\Support\Facades\Mail;

use App\Mail\NewUser;
use App\Mail\TestMail;
use App\Mail\CustomMail;
use Carbon\Carbon;

class Other
{


	public static function reset_email($id)
	{
		$user = User::find($id);
        $mail_array = [$user->email];
        Mail::to($mail_array)->cc(['joelkith@gmail.com'])->send(new NewUser($user));
	}


    public static function send_test()
    {
        Mail::to(['joelkith@gmail.com'])->send(new CustomMail(null));
    }

    public static function create_other_weeklies()
    {
        Dispensing::weeklies_table('d_prep_new');
        Dispensing::weeklies_table('d_vmmc_circ');

        Dispensing::insert_weekly_rows(2019, 'd_prep_new');
        Dispensing::insert_weekly_rows(2019, 'd_vmmc_circ');

        Dispensing::insert_weekly_rows(2020, 'd_prep_new');
        Dispensing::insert_weekly_rows(2020, 'd_vmmc_circ');
    }
    
    public static function send_pns()
    {
        $users = User::where('user_type_id', 3)->get();

        foreach ($users as $user) {
            Mail::to($user->email)->cc(['joelkith@gmail.com'])->send(new CustomMail($user));
        }
    }

    public static function other_targets()
    {
        $table_name = 't_non_mer';
        $sql = "CREATE TABLE `{$table_name}` (
                    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    facility int(10) UNSIGNED DEFAULT 0,
                    financial_year smallint(4) UNSIGNED DEFAULT 0,
                    viremia_beneficiaries int(10) DEFAULT NULL,
                    viremia_target int(10) DEFAULT NULL,
                    dsd_beneficiaries int(10) DEFAULT NULL,
                    dsd_target int(10) DEFAULT NULL,
                    otz_beneficiaries int(10) DEFAULT NULL,
                    otz_target int(10) DEFAULT NULL,
                    men_clinic_beneficiaries int(10) DEFAULT NULL,
                    men_clinic_target int(10) DEFAULT NULL,

                    dateupdated date DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `identifier`(`facility`, `financial_year`),
                    KEY `facility` (`facility`)
                );
        ";
        DB::connection('mysql_wr')->statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::connection('mysql_wr')->statement($sql);
    }

	public static function insert_others($year)
	{
		$table_name = 't_non_mer';
		$i=0;
		$data_array = [];
		
		$facilities = Facility::select('id')->get();
		foreach ($facilities as $k => $val) {
			$data_array[$i] = array('financial_year' => $year, 'facility' => $val->id);
			$i++;

			if ($i == 200) {
				DB::connection('mysql_wr')->table($table_name)->insert($data_array);
				$data_array=null;
		    	$i=0;
			}
		}

		if($data_array) DB::connection('mysql_wr')->table($table_name)->insert($data_array);
	}

	public static function partner_targets()
	{
		$table_name = 'p_non_mer';
    	$sql = "CREATE TABLE `{$table_name}` (
    				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    				partner tinyint(3) UNSIGNED DEFAULT 0,
    				financial_year smallint(4) UNSIGNED DEFAULT 0,
    				viremia int(10) DEFAULT NULL,
    				dsd int(10) DEFAULT NULL,
    				otz int(10) DEFAULT NULL,
    				men_clinic int(10) DEFAULT NULL,
	        		dateupdated date DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `identifier`(`partner`, `financial_year`),
					KEY `partner` (`partner`)
				);
        ";
        DB::connection('mysql_wr')->statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::connection('mysql_wr')->statement($sql);
	}

	public static function insert_partner_nonmer($year)
	{
		$table_name = 'p_non_mer';
		$i=0;
		$data_array = [];
		
		$partners = Partner::select('id')->get();
		foreach ($partners as $k => $val) {
			$data_array[$i] = array('financial_year' => $year, 'partner' => $val->id);
			$i++;

			if ($i == 200) {
				DB::connection('mysql_wr')->table($table_name)->insert($data_array);
				$data_array=null;
		    	$i=0;
			}
		}

		if($data_array) DB::connection('mysql_wr')->table($table_name)->insert($data_array);
	}

    public static function facility_targets()
    {
        $table_name = 't_facility_target';
        $sql = "CREATE TABLE `{$table_name}` (
                    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    facility int(10) UNSIGNED DEFAULT 0,
                    financial_year smallint(4) UNSIGNED DEFAULT 0,
                    gbv int(10) UNSIGNED DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `identifier`(`facility`, `financial_year`),
                    KEY `facility` (`facility`)
                );
        ";
        DB::connection('mysql_wr')->statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::connection('mysql_wr')->statement($sql);
    }

    public static function insert_facility_targets($year)
    {
        $table_name = 't_facility_target';
        $i=0;
        $data_array = [];
        
        $partners = Facility::select('id')->get();
        foreach ($partners as $k => $val) {
            $data_array[$i] = array('financial_year' => $year, 'facility' => $val->id);
            $i++;

            if ($i == 200) {
                DB::connection('mysql_wr')->table($table_name)->insert($data_array);
                $data_array=null;
                $i=0;
            }
        }

        if($data_array) DB::connection('mysql_wr')->table($table_name)->insert($data_array);
    }

    public static function insert_ward_targets($year)
    {
        $table_name = 't_ward_target';
        $i=0;
        $data_array = [];
        
        $partners = Ward::select('id')->get();
        foreach ($partners as $k => $val) {
            $data_array[$i] = array('financial_year' => $year, 'ward_id' => $val->id);
            $i++;

            if ($i == 200) {
                DB::connection('mysql_wr')->table($table_name)->insert($data_array);
                $data_array=null;
                $i=0;
            }
        }

        if($data_array) DB::connection('mysql_wr')->table($table_name)->insert($data_array);
    }

	public static function partner_indicators()
	{
		$table_name = 'p_early_indicators';
    	$sql = "CREATE TABLE `{$table_name}` (
    				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,

    				partner tinyint(3) UNSIGNED DEFAULT 0,
    				county tinyint(3) UNSIGNED DEFAULT 0,

    				year smallint(4) UNSIGNED DEFAULT 0,
    				month tinyint(3) UNSIGNED DEFAULT 0,
    				financial_year smallint(4) UNSIGNED DEFAULT 0,
    				quarter tinyint(3) UNSIGNED DEFAULT 0,

					tested int(10) DEFAULT NULL,    				
					positive int(10) DEFAULT NULL,    				
					new_art int(10) DEFAULT NULL,    				
					linkage double(6, 4) DEFAULT NULL,  

					current_tx int(10) DEFAULT NULL,    				
					net_new_tx int(10) DEFAULT NULL,    				
					vl_total int(10) DEFAULT NULL,    				
					eligible_for_vl int(10) DEFAULT NULL, 

					pmtct int(10) DEFAULT NULL,
					pmtct_stat int(10) DEFAULT NULL,
					pmtct_new_pos int(10) DEFAULT NULL,
					pmtct_known_pos int(10) DEFAULT NULL,
					pmtct_total_pos int(10) DEFAULT NULL,

					art_pmtct int(10) DEFAULT NULL,
					art_uptake_pmtct int(10) DEFAULT NULL,

					eid_lt_2m int(10) DEFAULT NULL,
					eid_lt_12m int(10) DEFAULT NULL,
					eid_total int(10) DEFAULT NULL,
					eid_pos int(10) DEFAULT NULL,

	        		dateupdated date DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `specific_identifier`(`partner`, `county`, `year`, `month`),
					KEY `identifier`(`partner`, `county`, `financial_year`, `quarter`),
					KEY `p_identifier`(`partner`, `financial_year`, `quarter`),
					KEY `c_identifier`(`county`, `financial_year`, `quarter`),
					KEY `partner` (`partner`),
					KEY `county` (`county`)
				);
        ";
        DB::connection('mysql_wr')->statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::connection('mysql_wr')->statement($sql);
	}

	public static function partner_indicators_insert($year=null)
	{
		if(!$year) $year = date('Y');
		$table_name = 'p_early_indicators';

        $partners = DB::table('partners')->get();
        $counties = DB::table('countys')->get();

		$i=0;
		$data_array = [];

        $periods = Period::where(['year' => $year])->get();

		foreach ($periods as $period) { 
			foreach ($partners as $partner) {
				foreach ($counties as $county) {
					$data = ['period_id' => $period->id, 'partner' => $partner->id, 'county' => $county->id];
					// $data = array_merge($data, $fq);

					$data_array[$i] = $data;
					$i++;

					if ($i == 200) {
						DB::connection('mysql_wr')->table($table_name)->insert($data_array);
						$data_array=null;
				    	$i=0;
					}
				}
			}
		}
		if($data_array) DB::connection('mysql_wr')->table($table_name)->insert($data_array);
	}

	public static function pns_table()
	{
		$table_name = 'd_pns';
    	$sql = "CREATE TABLE `{$table_name}` (
    				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    				facility int(10) UNSIGNED DEFAULT 0,

    				year smallint(4) UNSIGNED DEFAULT 0,
    				month tinyint(3) UNSIGNED DEFAULT 0,
    				financial_year smallint(4) UNSIGNED DEFAULT 0,
    				quarter tinyint(3) UNSIGNED DEFAULT 0,

    				screened_unknown_m int(10) DEFAULT NULL,
    				screened_unknown_f int(10) DEFAULT NULL,
    				screened_below_1 int(10) DEFAULT NULL,
    				screened_below_10 int(10) DEFAULT NULL,
    				screened_below_15_m int(10) DEFAULT NULL,
    				screened_below_15_f int(10) DEFAULT NULL,
    				screened_below_20_m int(10) DEFAULT NULL,
    				screened_below_20_f int(10) DEFAULT NULL,
    				screened_below_25_m int(10) DEFAULT NULL,
    				screened_below_25_f int(10) DEFAULT NULL,
    				screened_below_30_m int(10) DEFAULT NULL,
    				screened_below_30_f int(10) DEFAULT NULL,
    				screened_below_50_m int(10) DEFAULT NULL,
    				screened_below_50_f int(10) DEFAULT NULL,
    				screened_above_50_m int(10) DEFAULT NULL,
    				screened_above_50_f int(10) DEFAULT NULL,

    				contacts_identified_unknown_m int(10) DEFAULT NULL,
    				contacts_identified_unknown_f int(10) DEFAULT NULL,
    				contacts_identified_below_1 int(10) DEFAULT NULL,
    				contacts_identified_below_10 int(10) DEFAULT NULL,
    				contacts_identified_below_15_m int(10) DEFAULT NULL,
    				contacts_identified_below_15_f int(10) DEFAULT NULL,
    				contacts_identified_below_20_m int(10) DEFAULT NULL,
    				contacts_identified_below_20_f int(10) DEFAULT NULL,
    				contacts_identified_below_25_m int(10) DEFAULT NULL,
    				contacts_identified_below_25_f int(10) DEFAULT NULL,
    				contacts_identified_below_30_m int(10) DEFAULT NULL,
    				contacts_identified_below_30_f int(10) DEFAULT NULL,
    				contacts_identified_below_50_m int(10) DEFAULT NULL,
    				contacts_identified_below_50_f int(10) DEFAULT NULL,
    				contacts_identified_above_50_m int(10) DEFAULT NULL,
    				contacts_identified_above_50_f int(10) DEFAULT NULL,

                    pos_contacts_unknown_m int(10) DEFAULT NULL,
                    pos_contacts_unknown_f int(10) DEFAULT NULL,
                    pos_contacts_below_1 int(10) DEFAULT NULL,
                    pos_contacts_below_10 int(10) DEFAULT NULL,
                    pos_contacts_below_15_m int(10) DEFAULT NULL,
                    pos_contacts_below_15_f int(10) DEFAULT NULL,
                    pos_contacts_below_20_m int(10) DEFAULT NULL,
                    pos_contacts_below_20_f int(10) DEFAULT NULL,
                    pos_contacts_below_25_m int(10) DEFAULT NULL,
                    pos_contacts_below_25_f int(10) DEFAULT NULL,
                    pos_contacts_below_30_m int(10) DEFAULT NULL,
                    pos_contacts_below_30_f int(10) DEFAULT NULL,
                    pos_contacts_below_50_m int(10) DEFAULT NULL,
                    pos_contacts_below_50_f int(10) DEFAULT NULL,
                    pos_contacts_above_50_m int(10) DEFAULT NULL,
                    pos_contacts_above_50_f int(10) DEFAULT NULL,

                    eligible_contacts_unknown_m int(10) DEFAULT NULL,
                    eligible_contacts_unknown_f int(10) DEFAULT NULL,
                    eligible_contacts_below_1 int(10) DEFAULT NULL,
                    eligible_contacts_below_10 int(10) DEFAULT NULL,
                    eligible_contacts_below_15_m int(10) DEFAULT NULL,
                    eligible_contacts_below_15_f int(10) DEFAULT NULL,
                    eligible_contacts_below_20_m int(10) DEFAULT NULL,
                    eligible_contacts_below_20_f int(10) DEFAULT NULL,
                    eligible_contacts_below_25_m int(10) DEFAULT NULL,
                    eligible_contacts_below_25_f int(10) DEFAULT NULL,
                    eligible_contacts_below_30_m int(10) DEFAULT NULL,
                    eligible_contacts_below_30_f int(10) DEFAULT NULL,
                    eligible_contacts_below_50_m int(10) DEFAULT NULL,
                    eligible_contacts_below_50_f int(10) DEFAULT NULL,
                    eligible_contacts_above_50_m int(10) DEFAULT NULL,
                    eligible_contacts_above_50_f int(10) DEFAULT NULL,

                    contacts_tested_unknown_m int(10) DEFAULT NULL,
                    contacts_tested_unknown_f int(10) DEFAULT NULL,
                    contacts_tested_below_1 int(10) DEFAULT NULL,
                    contacts_tested_below_10 int(10) DEFAULT NULL,
                    contacts_tested_below_15_m int(10) DEFAULT NULL,
                    contacts_tested_below_15_f int(10) DEFAULT NULL,
                    contacts_tested_below_20_m int(10) DEFAULT NULL,
                    contacts_tested_below_20_f int(10) DEFAULT NULL,
                    contacts_tested_below_25_m int(10) DEFAULT NULL,
                    contacts_tested_below_25_f int(10) DEFAULT NULL,
                    contacts_tested_below_30_m int(10) DEFAULT NULL,
                    contacts_tested_below_30_f int(10) DEFAULT NULL,
                    contacts_tested_below_50_m int(10) DEFAULT NULL,
                    contacts_tested_below_50_f int(10) DEFAULT NULL,
                    contacts_tested_above_50_m int(10) DEFAULT NULL,
                    contacts_tested_above_50_f int(10) DEFAULT NULL,

                    new_pos_unknown_m int(10) DEFAULT NULL,
                    new_pos_unknown_f int(10) DEFAULT NULL,
                    new_pos_below_1 int(10) DEFAULT NULL,
                    new_pos_below_10 int(10) DEFAULT NULL,
                    new_pos_below_15_m int(10) DEFAULT NULL,
                    new_pos_below_15_f int(10) DEFAULT NULL,
                    new_pos_below_20_m int(10) DEFAULT NULL,
                    new_pos_below_20_f int(10) DEFAULT NULL,
                    new_pos_below_25_m int(10) DEFAULT NULL,
                    new_pos_below_25_f int(10) DEFAULT NULL,
                    new_pos_below_30_m int(10) DEFAULT NULL,
                    new_pos_below_30_f int(10) DEFAULT NULL,
                    new_pos_below_50_m int(10) DEFAULT NULL,
                    new_pos_below_50_f int(10) DEFAULT NULL,
                    new_pos_above_50_m int(10) DEFAULT NULL,
                    new_pos_above_50_f int(10) DEFAULT NULL,

                    linked_haart_unknown_m int(10) DEFAULT NULL,
                    linked_haart_unknown_f int(10) DEFAULT NULL,
                    linked_haart_below_1 int(10) DEFAULT NULL,
                    linked_haart_below_10 int(10) DEFAULT NULL,
                    linked_haart_below_15_m int(10) DEFAULT NULL,
                    linked_haart_below_15_f int(10) DEFAULT NULL,
                    linked_haart_below_20_m int(10) DEFAULT NULL,
                    linked_haart_below_20_f int(10) DEFAULT NULL,
                    linked_haart_below_25_m int(10) DEFAULT NULL,
                    linked_haart_below_25_f int(10) DEFAULT NULL,
                    linked_haart_below_30_m int(10) DEFAULT NULL,
                    linked_haart_below_30_f int(10) DEFAULT NULL,
                    linked_haart_below_50_m int(10) DEFAULT NULL,
                    linked_haart_below_50_f int(10) DEFAULT NULL,
                    linked_haart_above_50_m int(10) DEFAULT NULL,
                    linked_haart_above_50_f int(10) DEFAULT NULL,

	        		dateupdated date DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `identifier`(`facility`, `year`, `month`),
					KEY `identifier_other`(`facility`, `financial_year`, `quarter`),
					KEY `facility` (`facility`),
					KEY `specific_time` (`year`, `month`),
					KEY `specific_period` (`financial_year`, `quarter`)
				);
        ";
        DB::connection('mysql_wr')->statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::connection('mysql_wr')->statement($sql);
	}


	public static function delete_data($id)
    {
		// $tables = DB::table('data_set_elements')->selectRaw('Distinct table_name')->get();
		// foreach ($tables as $key => $table) {
		// 	DB::connection('mysql_wr')->table($table->table_name)->where('facility', $id)->delete();
		// }
		// $tables = DB::table('data_set_elements')->selectRaw('Distinct targets_table_name')->get();
		// foreach ($tables as $key => $table) {
		// 	DB::connection('mysql_wr')->table($table->targets_table_name)->where('facility', $id)->delete();
		// }
		// DB::connection('mysql_wr')->table("d_regimen_totals")->where('facility', $id)->delete();

        $tables = DB::select("show tables");
        foreach ($tables as $key => $row) {
            $t = $row->Tables_in_hcm;
            if(!\Str::startsWith($row->Tables_in_hcm, ['d_', 'm_']) || in_array($t, ['p_early_indicators', 'd_dispensing'])) continue;
            DB::connection('mysql_wr')->table($row->Tables_in_hcm)->where('facility', $id)->delete();
        }
        DB::connection('mysql_wr')->table('facilitys')->where('id', $id)->delete();
	}

}
