<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDHfrSubmissionEtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::connection('mysql_etl')->hasTable('d_hfr_submission')) return;
        Schema::connection('mysql_etl')->create('d_hfr_submission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id');
            $table->string('week_id')->nullable(); 
            $table->string('facility')->nullable(); 
            $table->string('hts_tst_below_15_female')->nullable(); 
            $table->string('hts_tst_below_15_male')->nullable(); 
            $table->string('hts_tst_above_15_female')->nullable(); 
            $table->string('hts_tst_above_15_male')->nullable(); 
            $table->string('hts_tst_pos_below_15_female')->nullable(); 
            $table->string('hts_tst_pos_below_15_male')->nullable(); 
            $table->string('hts_tst_pos_above_15_female')->nullable(); 
            $table->string('hts_tst_pos_above_15_male')->nullable(); 
            $table->string('tx_new_below_15_female')->nullable(); 
            $table->string('tx_new_below_15_male')->nullable(); 
            $table->string('tx_new_above_15_female')->nullable(); 
            $table->string('tx_new_above_15_male')->nullable(); 
            $table->string('vmmc_circ_below_15_male')->nullable(); 
            $table->string('vmmc_circ_above_15_male')->nullable(); 
            $table->string('prep_new_below_15_female')->nullable(); 
            $table->string('prep_new_below_15_male')->nullable(); 
            $table->string('prep_new_above_15_female')->nullable(); 
            $table->string('prep_new_above_15_male')->nullable(); 
            $table->string('tx_curr_below_15_female')->nullable(); 
            $table->string('tx_curr_below_15_male')->nullable(); 
            $table->string('tx_curr_above_15_female')->nullable(); 
            $table->string('tx_curr_above_15_male')->nullable(); 
            $table->string('tx_mmd_below_15_female_less_3m')->nullable(); 
            $table->string('tx_mmd_below_15_male_less_3m')->nullable(); 
            $table->string('tx_mmd_above_15_female_less_3m')->nullable(); 
            $table->string('tx_mmd_above_15_male_less_3m')->nullable(); 
            $table->string('tx_mmd_below_15_female_3_5m')->nullable(); 
            $table->string('tx_mmd_below_15_male_3_5m')->nullable(); 
            $table->string('tx_mmd_above_15_female_3_5m')->nullable(); 
            $table->string('tx_mmd_above_15_male_3_5m')->nullable(); 
            $table->string('tx_mmd_below_15_female_above_6m')->nullable(); 
            $table->string('tx_mmd_below_15_male_above_6m')->nullable(); 
            $table->string('tx_mmd_above_15_female_above_6m')->nullable(); 
            $table->string('tx_mmd_above_15_male_above_6m')->nullable(); 
            $table->timestamps();    
            
        });


       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_hfr_submission_etls');
    }
}
