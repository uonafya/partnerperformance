<?php

namespace App\Imports;

use DB;
use \App\Period;
use \App\Facility;
use \App\Lookup;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NonMerImport implements OnEachRow, WithHeadingRow
{

    public function onRow(Row $row)
    {
    	$row = json_decode(json_encode($row->toArray()));

		if(!is_numeric($row->mfl_code) || (is_numeric($row->mfl_code) && $row->mfl_code < 10000)) return;		

		$fac = Facility::where('facilitycode', $row->mfl_code)->first();

		if(!$fac) return;
		
		/*if($fac->partner != auth()->user()->partner_id){
			$fac->partner = auth()->user()->partner_id;
			$fac->save();

			DB::table('apidb.facilitys')->where('facilitycode', $fac->facilitycode)->update(['partner' => auth()->user()->partner_id]);
			DB::table('national_db.facilitys')->where('facilitycode', $fac->facilitycode)->update(['partner' => auth()->user()->partner_id]);
		}*/

		// if($fac->partner != auth()->user()->partner_id) return;

		$fac->fill([
			'is_pns' => Lookup::clean_boolean($row->is_pns_yesno), 
			'is_viremia' => Lookup::clean_boolean($row->is_viremia_yesno), 
			'is_dsd' => Lookup::clean_boolean($row->is_dsd_yesno), 
			'is_otz' => Lookup::clean_boolean($row->is_otz_yesno), 
			'is_men_clinic' => Lookup::clean_boolean($row->is_men_clinic_yesno),
		]);

		$viremia = (int) $row->viremia_beneficiaries ?? null;
		$dsd = (int) $row->dsd_beneficiaries ?? null;
		$otz = (int) $row->otz_beneficiaries ?? null;
		$men_clinic = (int) $row->men_clinic_beneficiaries ?? null;

		if(env('APP_ENV') != 'testing') {

			DB::connection('mysql_wr')->table('t_non_mer')
				->where(['facility' => $fac->id, 'financial_year' => $row->financial_year])
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
}
