<?php

namespace App;

use App\BaseModel;

class Facility extends BaseModel
{
	protected $connection = 'mysql_wr';
	protected $table = 'facilitys';


    public function scopeEligible($query, $offset=0)
    {
        return $query->whereNotNull('DHIScode')
        		->where('DHIScode', '!=', '0')
        		->where('invalid_dhis', 0)
        		->limit(50)->offset($offset);
    }

	public function ward()
	{
		return $this->belongsTo('App\Ward');
	}

	public function subcounty()
	{
		return $this->belongsTo('App\Subcounty', 'subcounty_id');
	}

	public function supportedFacility()
	{
		return $this->hasMany('App\SupportedFacility', 'facility_id');
	}

    public function changePartner($partner_id, $start_of_support)
    {
        $existing = $this->supportedFacility()->where('start_of_support', $start_of_support)->delete();

    	$newSupportedFacility = SupportedFacility::firstOrCreate([
    		'facility_id' => $this->id,
    		'partner_id' => $partner_id,
    		'start_of_support' => $start_of_support,
    	]);

    	$prevSupportedFacility = $this->supportedFacility()->whereNull('end_of_support')->where('start_of_support', '!=', $start_of_support)->first();
    	if(!$prevSupportedFacility) return;
        $prevSupportedFacility->end_of_support = $newSupportedFacility->start_of_support->subDay()->toDateString();
    	$prevSupportedFacility->save();
    }

	public static function transform($load)
    {
        return $load->getCOllection()
                    ->map(function($item){
                        return [
                            'old_id' => $item->id,
							'facilitycode' => $item->facilitycode,   
							'district' => $item->district,  
							'subcounty_id' => $item->subcounty_id,  
							'ward_id' => $item->ward_id,  
							'name' => $item->name,   
							'new_name' => $item->new_name,  
							'lab' => $item->lab,   
							'partner' => $item->partner,  
							'ftype' => $item->ftype,  
							'DHIScode' => $item->DHIScode,  
							'facility_uid' => $item->facility_uid,   
							'community' => $item->community,   
							'is_pns' => $item->is_pns,   
							'is_viremia' => $item->is_viremia,   
							'is_dsd' => $item->is_dsd,   
							'is_otz' => $item->is_otz,   
							'is_men_clinic' => $item->is_men_clinic,   
							'is_surge' => $item->is_surge,   
							'longitude' => $item->longitude,  
							'latitude' => $item->latitude,  
							'burden' => $item->burden,  
							'artpatients' => $item->artpatients,   
							'pmtctnos' => $item->pmtctnos,   
							'Mless15' => $item->Mless15,   
							'Mmore15' => $item->Mmore15,   
							'Fless15' => $item->Fless15,   
							'Fmore15' => $item->Fmore15,   
							'totalartmar' => $item->totalartmar,   
							'totalartsep17' => $item->totalartsep17,   
							'totalartsep15' => $item->totalartsep15,   
							'asofdate' => $item->asofdate,  
							'partnerold' => $item->partnerold,   
							'partner2' => $item->partner2,    
							'partner3' => $item->partner3,  
							'partner4' => $item->partner4,  
							'partner5' => $item->partner5,    
							'partner6' => $item->partner6,    
							'telephone' => $item->telephone,   
							'telephone2' => $item->telephone2,   
							'telephone3' => $item->telephone3,   
							'fax' => $item->fax,   
							'email' => $item->email,  
							'PostalAddress' => $item->PostalAddress,   
							'contactperson' => $item->contactperson,  
							'contacttelephone' => $item->contacttelephone,   
							'contacttelephone2' => $item->contacttelephone2,   
							'contacttelephone3' => $item->contacttelephone3,   
							'physicaladdress' => $item->physicaladdress,   
							'ContactEmail' => $item->ContactEmail,   
							'ContactEmail2' => $item->ContactEmail2,   
							'ContactEmail3' => $item->ContactEmail3,   
							'ContactEmail4' => $item->ContactEmail4,   
							'ContactEmail5' => $item->ContactEmail5,   
							'ContactEmail6' => $item->ContactEmail6,   
							'subcountyemail' => $item->subcountyemail,   
							'countyemail' => $item->countyemail,   
							'partneremail' => $item->partneremail,   
							'originalID' => $item->originalID,   
							'partnerlabmail' => $item->partnerlabmail,   
							'partnerpointmail' => $item->partnerpointmail,   
							'dmltemail' => $item->dmltemail,   
							'dtlcemail' => $item->dtlcemail,   
							'serviceprovider' => $item->serviceprovider,   
							'smsprinterphoneno' => $item->smsprinterphoneno,   
							'smssecondarycontact' => $item->smssecondarycontact,   
							'smsprimarycontact' => $item->smsprimarycontact,   
							'smscontactperson' => $item->smscontactperson,   
							'smsprinter' => $item->smsprinter,   
							'G4Slocation' => $item->G4Slocation,   
							'G4Sphone1' => $item->G4Sphone1,   
							'G4Sphone2' => $item->G4Sphone2,   
							'G4Sphone3' => $item->G4Sphone3,   
							'G4Sfax' => $item->G4Sfax,   
							'PMTCT' => $item->PMTCT,   
							'ART' => $item->ART,   
							'Flag' => $item->Flag,   
							'sent' => $item->sent,  
							'synched' => $item->synched,   
							'invalid_dhis' => $item->invalid_dhis,   
							
                        ];
                    });
    }
}
