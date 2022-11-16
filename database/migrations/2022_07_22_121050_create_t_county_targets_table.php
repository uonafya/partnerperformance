<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTCountyTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::connection('mysql_etl')->hasTable('t_county_target')) return;

        Schema::connection('mysql_etl')->create('t_county_target', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('county_id')->nullable() ;
            $table->integer('partner_id')->nullable() ;
            $table->integer('financial_year')->nullable() ;
            $table->integer('gbv')->nullable() ;
            $table->integer('pep')->nullable() ;
            $table->integer('physical_emotional_violence')->nullable() ;
            $table->integer('sexual_violence')->nullable() ;
            $table->integer('sexual_violence_post_rape_care')->nullable() ;
            $table->integer('hts_tst_above_15_female')->nullable() ;
            $table->integer('hts_tst_above_15_male')->nullable() ;
            $table->integer('hts_tst_below_15_female')->nullable() ;
            $table->integer('hts_tst_below_15_male')->nullable() ;
            $table->integer('hts_tst_pos_above_15_female')->nullable() ;
            $table->integer('hts_tst_pos_above_15_male')->nullable() ;
            $table->integer('hts_tst_pos_below_15_female')->nullable() ;
            $table->integer('hts_tst_pos_below_15_male')->nullable() ;
            $table->integer('prep_new_above_15_female')->nullable() ;
            $table->integer('prep_new_above_15_male')->nullable() ;
            $table->integer('prep_new_below_15_female')->nullable() ;
            $table->integer('prep_new_below_15_male')->nullable() ;
            $table->integer('tx_curr_above_15_female')->nullable() ;
            $table->integer('tx_curr_above_15_male')->nullable() ;
            $table->integer('tx_curr_below_15_female')->nullable() ;
            $table->integer('tx_curr_below_15_male')->nullable() ;
            $table->integer('tx_new_above_15_female')->nullable() ;
            $table->integer('tx_new_above_15_male')->nullable() ;
            $table->integer('tx_new_below_15_female')->nullable() ;
            $table->integer('tx_new_below_15_male')->nullable() ;
            $table->integer('vmmc_circ_above_15_male')->nullable() ;
            $table->integer('vmmc_circ_below_15_male')->nullable() ;
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
        Schema::dropIfExists('t_county_target');
    }
}
