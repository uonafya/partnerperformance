<?php

namespace App\Imports;

use DB;
use \App\Period;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IndicatorImport implements OnEachRow, WithHeadingRow
{


    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray()));

		$update_data = [
			'tested' => (int) $row->tested ?? null,
			'positive' => (int) $row->positives ?? null,
			'new_art' => (int) $row->new_on_art ?? null,
			'linkage' => (double) $row->linkage_percentage ?? null,
			'current_tx' => (int) $row->current_on_art ?? null,
			'net_new_tx' => (int) $row->net_new_on_art ?? null,
			'vl_total' => (int) $row->vl_total ?? null,	
			'eligible_for_vl' => (int) $row->eligible_for_vl ?? null,	
			'pmtct' => (int) $row->pmtct ?? null,	
			'pmtct_stat' => (int) $row->pmtct_stat ?? null,	
			'pmtct_new_pos' => (int) $row->pmtct_new_positives ?? null,	
			'art_pmtct' => (int) $row->art_pmtct ?? null,	
			'art_uptake_pmtct' => (int) $row->art_uptake_pmtct ?? null,	
			'eid_lt_2m' => (int) $row->eid_less_2_months ?? null,	
			'eid_lt_12m' => (int) $row->eid_less_12_months ?? null,	
			'eid_total' => (int) $row->eid_total ?? null,	
			'eid_pos' => (int) $row->eid_positives ?? null,
			'dateupdated' => date('Y-m-d'),	
		];

		$county = DB::table('countys')->where('countymflcode', $row->county_mfl)->first();

		if(!$county) return;
		$period = Period::where(['financial_year' => $row->financial_year, 'month' => $row->month])->first();
		if(!$period) return;

		if(env('APP_ENV') != 'testing'){
			DB::connection('mysql_wr')->table('p_early_indicators')
			->where([
				'county' => $county->id, 'partner' => auth()->user()->partner_id, 'period_id' => $period->id,					
			])
			->update($update_data);
		}

    }
}
