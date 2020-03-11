<?php

namespace App;

use DB;

class Insert
{

    public static function insert_periods($year=null)
    {
        if(!$year) $year = date('Y');
        $data_array = [];

        for ($month=1; $month < 13; $month++) { 
            $data = array('year' => $year, 'month' => $month);
            $data = array_merge($data, Synch::get_financial_year_quarter($year, $month) );
            $data_array[] = $data;
        }

        DB::connection('mysql_wr')->table('periods')->insert($data_array);
    }

	public static function insert_rows($year=null)
	{
		if(!$year) $year = date('Y');
        $tables = DB::select("show tables");
		$facilities = Facility::select('id')->get();
		$periods = Period::where(['year' => $year])->get();

        foreach ($tables as $key => $row) {
            $table_name = $row->Tables_in_hcm;
            if(!starts_with($table_name, ['d_', 'm_']) || in_array($table_name, ['d_tx_curr', 'd_dispensing'])) continue;

            $columns = collect(DB::select("show columns from `" . $table_name . '`'));
            $p = $columns->where('Field', 'period_id')->first();
            if(!$p) continue;

            $i = 0;
            $data = [];

			echo "Begin entry for {$table_name} for {$year} as {date('Y-m-d H:i:s')}  \n";

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

			echo "Completed entry for {$table_name} for {$year} as {date('Y-m-d H:i:s')}  \n";
        }
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
}
