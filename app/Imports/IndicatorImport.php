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
    	$row = json_decode(json_encode($row));

		$update_data = [
			'tested' => (int) $value->tested ?? null,
			'positive' => (int) $value->positives ?? null,
			'new_art' => (int) $value->new_on_art ?? null,
			'linkage' => (double) $value->linkage_percentage ?? null,
			'current_tx' => (int) $value->current_on_art ?? null,
			'net_new_tx' => (int) $value->net_new_on_art ?? null,
			'vl_total' => (int) $value->vl_total ?? null,	
			'eligible_for_vl' => (int) $value->eligible_for_vl ?? null,	
			'pmtct' => (int) $value->pmtct ?? null,	
			'pmtct_stat' => (int) $value->pmtct_stat ?? null,	
			'pmtct_new_pos' => (int) $value->pmtct_new_positives ?? null,	
			'art_pmtct' => (int) $value->art_pmtct ?? null,	
			'art_uptake_pmtct' => (int) $value->art_uptake_pmtct ?? null,	
			'eid_lt_2m' => (int) $value->eid_less_2_months ?? null,	
			'eid_lt_12m' => (int) $value->eid_less_12_months ?? null,	
			'eid_total' => (int) $value->eid_total ?? null,	
			'eid_pos' => (int) $value->eid_positives ?? null,
			'dateupdated' => date('Y-m-d'),	
		];

		$county = DB::table('countys')->where('countymflcode', $value->county_mfl)->first();

		if(!$county) continue;
		$period = Period::where(['financial_year' => $value->financial_year, 'month' => $value->month])->first();
		if(!$period) continue;

		DB::connection('mysql_wr')->table('p_early_indicators')
			->where([
				'county' => $county->id, 'partner' => auth()->user()->partner_id, 'period_id' => $period->id,					
			])
			->update($update_data);

    }
}
