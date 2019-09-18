<?php

namespace App\Imports;

use DB;
use \App\Period;
use \App\Facility;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NonMerImport implements OnEachRow, WithHeadingRow
{

    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row));

		if(!is_numeric($value->mfl_code) || (is_numeric($value->mfl_code) && $value->mfl_code < 10000)) continue;		

		$fac = Facility::where('facilitycode', $value->mfl_code)->first();

		if(!$fac) continue;
		
		/*if($fac->partner != auth()->user()->partner_id){
			$fac->partner = auth()->user()->partner_id;
			$fac->save();

			DB::table('apidb.facilitys')->where('facilitycode', $fac->facilitycode)->update(['partner' => auth()->user()->partner_id]);
			DB::table('national_db.facilitys')->where('facilitycode', $fac->facilitycode)->update(['partner' => auth()->user()->partner_id]);
		}*/

		// if($fac->partner != auth()->user()->partner_id) continue;

		$fac->fill([
			'is_pns' => Lookup::clean_boolean($value->is_pns_yesno), 
			'is_viremia' => Lookup::clean_boolean($value->is_viremia_yesno), 
			'is_dsd' => Lookup::clean_boolean($value->is_dsd_yesno), 
			'is_otz' => Lookup::clean_boolean($value->is_otz_yesno), 
			'is_men_clinic' => Lookup::clean_boolean($value->is_men_clinic_yesno),
		]);

		$viremia = (int) $value->viremia_beneficiaries ?? null;
		$dsd = (int) $value->dsd_beneficiaries ?? null;
		$otz = (int) $value->otz_beneficiaries ?? null;
		$men_clinic = (int) $value->men_clinic_beneficiaries ?? null;

		DB::connection('mysql_wr')->table('t_non_mer')
			->where(['facility' => $fac->id, 'financial_year' => $value->financial_year])
			->update([
				'viremia_beneficiaries' => $viremia,
				'dsd_beneficiaries' => $dsd,
				'otz_beneficiaries' => $otz,
				'men_clinic_beneficiaries' => $men_clinic,
			]);

		if(!$fac->is_viremia && $viremia) $fac->is_viremia = 1;
		if(!$fac->is_dsd && $dsd) $fac->is_dsd = 1;
		if(!$fac->is_otz && $otz) $fac->is_otz = 1;
		if(!$fac->is_men_clinic && $men_clinic) $fac->is_men_clinic = 1;
		$fac->save();

    }
}
