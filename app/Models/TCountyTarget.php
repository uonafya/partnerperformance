<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TCountyTarget extends Model
{
    public $data;
    protected $table = 't_county_target';
    protected $connection = 'mysql_wr';

    public static function transform($load)
    {
       return $load->map(function($item){
            // return $item;
            return [
                'id' => $item->id,
                'county_id' => $item->county_id ,
                'partner_id' => $item->partner_id ,
                'financial_year' => $item->financial_year ,
                'gbv' => $item->gbv ,
                'pep' => $item->pep ,
                'physical_emotional_violence' => $item->physical_emotional_violence ,
                'sexual_violence' => $item->sexual_violence ,
                'sexual_violence_post_rape_care' => $item->sexual_violence_post_rape_care ,
                'hts_tst_above_15_female' => $item->hts_tst_above_15_female ,
                'hts_tst_above_15_male' => $item->hts_tst_above_15_male ,
                'hts_tst_below_15_female' => $item->hts_tst_below_15_female ,
                'hts_tst_below_15_male' => $item->hts_tst_below_15_male ,
                'hts_tst_pos_above_15_female' => $item->hts_tst_pos_above_15_female ,
                'hts_tst_pos_above_15_male' => $item->hts_tst_pos_above_15_male ,
                'hts_tst_pos_below_15_female' => $item->hts_tst_pos_below_15_female ,
                'hts_tst_pos_below_15_male' => $item->hts_tst_pos_below_15_male ,
                'prep_new_above_15_female' => $item->prep_new_above_15_female ,
                'prep_new_above_15_male' => $item->prep_new_above_15_male ,
                'prep_new_below_15_female' => $item->prep_new_below_15_female ,
                'prep_new_below_15_male' => $item->prep_new_below_15_male ,
                'tx_curr_above_15_female' => $item->tx_curr_above_15_female ,
                'tx_curr_above_15_male' => $item->tx_curr_above_15_male ,
                'tx_curr_below_15_female' => $item->tx_curr_below_15_female ,
                'tx_curr_below_15_male' => $item->tx_curr_below_15_male ,
                'tx_new_above_15_female' => $item->tx_new_above_15_female ,
                'tx_new_above_15_male' => $item->tx_new_above_15_male ,
                'tx_new_below_15_female' => $item->tx_new_below_15_female ,
                'tx_new_below_15_male' => $item->tx_new_below_15_male ,
                'vmmc_circ_above_15_male' => $item->vmmc_circ_above_15_male ,
                'vmmc_circ_below_15_male' => $item->vmmc_circ_below_15_male
                        
            ];
        });
    } 
}
