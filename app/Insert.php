<?php

namespace App;

use DB;
use Carbon\Carbon;


class Insert
{

    public static function insert_periods($year=null)
    {
        if(!$year) $year = date('Y');
        $data_array = [];

        for ($month=1; $month < 13; $month++) { 
            $data = array('year' => $year, 'month' => $month);
            $data = array_merge($data, Synch::get_financial_year_quarter($year, $month) );
            // $data_array[] = $data;
            $period = Period::firstOrCreate($data);
        }

        // DB::connection('mysql_wr')->table('periods')->insert($data_array);
    }

	public static function insert_rows($year=null)
	{
		if(!$year) $year = date('Y');
        $tables = DB::select("show tables");
		$facilities = Facility::select('id')->get();
		$periods = Period::where(['year' => $year])->get();
        // $periods = Period::where(['year' => 2019, 'financial_year' => 2020])->get();

        foreach ($tables as $key => $row) {
            $table_name = $row->Tables_in_hcm;
            if($table_name != 'd_cervical_cancer') continue;
            if(!\Str::startsWith($table_name, ['d_', 'm_']) || in_array($table_name, ['d_tx_curr', 'd_dispensing'])) continue;

            $columns = collect(DB::select("show columns from `" . $table_name . '`'));
            $p = $columns->where('Field', 'period_id')->first();
            if(!$p) continue;

            $i = 0;
            $data = [];

			echo "Begin entry for {$table_name} for {$year} at " . date('Y-m-d H:i:s') . "\n";

            foreach ($periods as $period) {
            	foreach ($facilities as $facility) {
            		$data[] = ['period_id' => $period->id, 'facility' => $facility->id ];
            		$i++;

            		if($i == 200){
            			DB::connection('mysql_wr')->table($table_name)->insert($data);
            			$i=0;
            			$data=[];
            		}
            	}
            }

            if($data) DB::connection('mysql_wr')->table($table_name)->insert($data);
			$i=0;
			$data=[];

			echo "Completed entry for {$table_name} for {$year} at " . date('Y-m-d H:i:s') . "\n";
        }
        return;
        self::insert_dispensing_rows($year);
        self::insert_tx_curr_rows($year);
        self::partner_indicators_insert($year);
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

    public static function insert_tx_curr_rows($year=null,$table_name = 'd_tx_curr')
    {
        if(!$year) $year = date('Y');

        $periods = Period::where(['year' => $year])->get();
        $modality = SurgeModality::where(['tbl_name' => $table_name])->first();
        $columns  = SurgeColumn::where(['modality_id' => $modality->id])->get();
        $facilities = Facility::select('id')->get();

        $i=0;
        $data_array = [];

        foreach ($periods as $period) {
            foreach ($facilities as $k => $facility) {
                foreach ($columns as $column) {
                    $data = ['period_id' => $period->id, 'facility' => $facility->id, 'column_id' => $column->id];
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

        echo 'Completed entry for ' . $table_name . " \n";
    }

    public static function insert_dispensing_rows($year=null,$table_name = 'd_dispensing')
    {
        if(!$year) $year = date('Y');

        $periods = Period::where(['year' => $year])->get();
        $genders = SurgeGender::all();
        $age_categories = AgeCategory::all();
        $facilities = Facility::select('id')->get();

        $i=0;
        $data_array = [];

        foreach ($periods as $period) {
            foreach ($facilities as $k => $facility) {
                foreach ($age_categories as $age_category) {
                    foreach ($genders as $gender) {
                        $data = ['period_id' => $period->id, 'facility' => $facility->id, 'age_category_id' => $age_category->id, 'gender_id' => $gender->id, ];
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
        }


        if($data_array) DB::connection('mysql_wr')->table($table_name)->insert($data_array);

        echo 'Completed entry for ' . $table_name . " \n";
    }



    // Week starts on Sunday
    // Week belongs to the month where the Saturday is
    // ISO 8601 states that the week begins on Monday
    // It also states that the week belongs to the month/year that the Thursday is in
    public static function insert_weeks($financial_year)
    {
        $year = $financial_year - 1;
        $dt = Carbon::createFromDate($year, 10, 1);
        $week = 1;

        // if($dt->dayOfWeek != 0){
        if($dt->dayOfWeekIso != 1){

            while(true){
                // if($dt->dayOfWeek == 0) break;
                if($dt->dayOfWeekIso == 1) break;
                $dt->addDay();
            }

            $data = [
                'week_number' => $week++,
                'start_date' => $dt->toDateString(),
                'year' => $dt->year,
                'month' => $dt->month,
                'end_date' => $dt->addDays(6)->toDateString(),
            ];

            $data = array_merge($data, Synch::get_financial_year_quarter($data['year'], $data['month']));
            $dt->addDay();

            $w = Week::create($data);
        }

        while(true) {
            $data = [
                'week_number' => $week++,
                'start_date' => $dt->toDateString(),
                'year' => $dt->year,
                'month' => $dt->month,
                'end_date' => $dt->addDays(6)->toDateString(),
            ];

            $data = array_merge($data, Synch::get_financial_year_quarter($data['year'], $data['month']));
            $dt->addDay();

            $w = new Week;
            $w->fill($data);
            if($w->financial_year != $financial_year) break;
            $w->save();
        }
        // DB::connection('mysql_wr')->statement("DELETE FROM weeks where week_number < 31 and financial_year = 2019;");
    }


    public static function insert_weekly_column_rows($year=null, $table_name='d_weeklies')
    {
        if(!$year){
            $year = date('Y');
            if(date('m') > 9) $year++;
        }

        $weeks = Week::where('financial_year', $year)->get();

        $modalities = SurgeModality::where(['tbl_name' => $table_name])->get();

        $i=0;
        $data_array = [];
        
        $facilities = Facility::select('id')->get();
        foreach ($modalities as $modality) {
            $columns  = SurgeColumn::where(['modality_id' => $modality->id])->get();
            foreach ($facilities as $fac) {
                foreach ($columns as $column) {
                    foreach ($weeks as $week) {
                        $data_array[$i] = ['week_id' => $week->id, 'facility' => $fac->id, 'column_id' => $column->id];
                        $i++;

                        if ($i == 30) {
                            DB::table($table_name)->insert($data_array);
                            $data_array=null;
                            $i=0;
                        }               
                    }
                }
            }
            echo 'Completed entry for ' . $modality->modality . " \n";
        }

        if($data_array) DB::table($table_name)->insert($data_array);
    }


    public static function insert_weekly_rows($year=null, $table_name='d_surge')
    {
        if(!$year){
            $year = date('Y');
            if(date('m') > 9) $year++;
        }

        $weeks = Week::where('financial_year', $year)->get();

        $i=0;
        $data_array = [];
        
        $facilities = Facility::select('id')->get();
        foreach ($weeks as $week) {
            $row = DB::table($table_name)->where(['week_id' => $week->id])->first();
            if($row) continue;

            foreach ($facilities as $fac) {

                $data_array[$i] = ['week_id' => $week->id, 'facility' => $fac->id];
                $i++;

                if ($i == 30) {
                    DB::table($table_name)->insert($data_array);
                    $data_array=[];
                    $i=0;
                }               
            }
        }

        if($data_array) DB::table($table_name)->insert($data_array);
    }

    public static function create_weeks($financial_year=null)
    {
        if(!$financial_year){
            $financial_year = date('Y');
            if(date('m') > 9) $financial_year++;
        }
        self::insert_weeks($financial_year);
        self::insert_weekly_rows($financial_year, 'd_surge');
        self::insert_weekly_column_rows($financial_year, 'd_weeklies');
        self::insert_weekly_column_rows($financial_year, 'd_prep_new');
        self::insert_weekly_column_rows($financial_year, 'd_vmmc_circ');
        self::insert_weekly_rows($financial_year, 'd_hfr_submission');
    }


    public static function fix_weeks()
    {
        $weeks = Week::all();
        foreach ($weeks as $key => $week) {
            $dt = Carbon::create($week->start_date);
            $week->year = $dt->year;
            $week->month = $dt->month;
            $week->fill(Synch::get_financial_year_quarter($week->year, $week->month));
            $week->save();
        }

        $financial_years = Week::selectRaw("distinct financial_year")->get();
        foreach ($financial_years as $financial_year) {
            $weeks = Week::where(['financial_year' => $financial_year->financial_year])->orderBy('start_date', 'ASC')->get();
            foreach ($weeks as $key => $week) {
                $week->week_number = $key+1;
                $week->save();
            }
        }
    }
}
