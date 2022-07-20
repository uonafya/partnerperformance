<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DHfrSubmission extends Model
{

    public $data;
    protected $connection = 'mysql_wr';
    protected $table = 'd_hfr_submission';

    public static function transform($load)
    {
        return $load->getCOllection()
                    ->map(function($item){
                        return [
                            'old_id' => $item->id,
                            'week_id' => $item->week_id, 
                            'facility' => $item->facility, 
                            'hts_tst_below_15_female' => $item->hts_tst_below_15_female, 
                            'hts_tst_below_15_male' => $item->hts_tst_below_15_male, 
                            'hts_tst_above_15_female' => $item->hts_tst_above_15_female, 
                            'hts_tst_above_15_male' => $item->hts_tst_above_15_male, 
                            'hts_tst_pos_below_15_female' => $item->hts_tst_pos_below_15_female, 
                            'hts_tst_pos_below_15_male' => $item->hts_tst_pos_below_15_male, 
                            'hts_tst_pos_above_15_female' => $item->hts_tst_pos_above_15_female, 
                            'hts_tst_pos_above_15_male' => $item->hts_tst_pos_above_15_male, 
                            'tx_new_below_15_female' => $item->tx_new_below_15_female, 
                            'tx_new_below_15_male' => $item->tx_new_below_15_male, 
                            'tx_new_above_15_female' => $item->tx_new_above_15_female, 
                            'tx_new_above_15_male' => $item->tx_new_above_15_male, 
                            'vmmc_circ_below_15_male' => $item->vmmc_circ_below_15_male, 
                            'vmmc_circ_above_15_male' => $item->vmmc_circ_above_15_male, 
                            'prep_new_below_15_female' => $item->prep_new_below_15_female, 
                            'prep_new_below_15_male' => $item->prep_new_below_15_male, 
                            'prep_new_above_15_female' => $item->prep_new_above_15_female, 
                            'prep_new_above_15_male' => $item->prep_new_above_15_male, 
                            'tx_curr_below_15_female' => $item->tx_curr_below_15_female, 
                            'tx_curr_below_15_male' => $item->tx_curr_below_15_male, 
                            'tx_curr_above_15_female' => $item->tx_curr_above_15_female, 
                            'tx_curr_above_15_male' => $item->tx_curr_above_15_male, 
                            'tx_mmd_below_15_female_less_3m' => $item->tx_mmd_below_15_female_less_3m, 
                            'tx_mmd_below_15_male_less_3m' => $item->tx_mmd_below_15_male_less_3m, 
                            'tx_mmd_above_15_female_less_3m' => $item->tx_mmd_above_15_female_less_3m, 
                            'tx_mmd_above_15_male_less_3m' => $item->tx_mmd_above_15_male_less_3m, 
                            'tx_mmd_below_15_female_3_5m' => $item->tx_mmd_below_15_female_3_5m, 
                            'tx_mmd_below_15_male_3_5m' => $item->tx_mmd_below_15_male_3_5m, 
                            'tx_mmd_above_15_female_3_5m' => $item->tx_mmd_above_15_female_3_5m, 
                            'tx_mmd_above_15_male_3_5m' => $item->tx_mmd_above_15_male_3_5m, 
                            'tx_mmd_below_15_female_above_6m' => $item->tx_mmd_below_15_female_above_6m, 
                            'tx_mmd_below_15_male_above_6m' => $item->tx_mmd_below_15_male_above_6m, 
                            'tx_mmd_above_15_female_above_6m' => $item->tx_mmd_above_15_female_above_6m, 
                            'tx_mmd_above_15_male_above_6m' => $item->tx_mmd_above_15_male_above_6m, 
                        ];
                    });
    }
}
