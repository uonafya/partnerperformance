<?php

namespace App;

use DB;

class Insert
{

    public static function insert_periods($year)
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
        Dispensing::insert_dispensing_rows($year);
        Dispensing::insert_tx_curr_rows($year);
	}
}
