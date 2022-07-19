<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDHfrSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::connection('mysql_etl')->hasTable('view_facilities_etls')){
            Schema::connection('mysql_etl')->create('d_hfr_submission', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
    
                
                // `week_id` 
                // `facility` 
                // `hts_tst_below_15_female` 
                // `hts_tst_below_15_male` 
                // `hts_tst_above_15_female` 
                // `hts_tst_above_15_male` 
                // `hts_tst_pos_below_15_female` 
                // `hts_tst_pos_below_15_male` 
                // `hts_tst_pos_above_15_female` 
                // `hts_tst_pos_above_15_male` 
                // `tx_new_below_15_female` 
                // `tx_new_below_15_male` 
                // `tx_new_above_15_female` 
                // `tx_new_above_15_male` 
                // `vmmc_circ_below_15_male` 
                // `vmmc_circ_above_15_male` 
                // `prep_new_below_15_female` 
                // `prep_new_below_15_male` 
                // `prep_new_above_15_female` 
                // `prep_new_above_15_male` 
                // `tx_curr_below_15_female` 
                // `tx_curr_below_15_male` 
                // `tx_curr_above_15_female` 
                // `tx_curr_above_15_male` 
                // `tx_mmd_below_15_female_less_3m` 
                // `tx_mmd_below_15_male_less_3m` 
                // `tx_mmd_above_15_female_less_3m` 
                // `tx_mmd_above_15_male_less_3m` 
                // `tx_mmd_below_15_female_3_5m` 
                // `tx_mmd_below_15_male_3_5m` 
                // `tx_mmd_above_15_female_3_5m` 
                // `tx_mmd_above_15_male_3_5m` 
                // `tx_mmd_below_15_female_above_6m` 
                // `tx_mmd_below_15_male_above_6m` 
                // `tx_mmd_above_15_female_above_6m` 
                // `tx_mmd_above_15_male_above_6m` 
            });
        }

       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_hfr_submissions');
    }
}
