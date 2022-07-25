<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTFacilityHfrTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // evry thing is Int
        if(Schema::connection('mysql_etl')->hasTable('t_facility_hfr_target')) return;

        Schema::connection('mysql_etl')->create('t_facility_hfr_target', function (Blueprint $table) {
            $table->bigIncrements('id'); 
               $table->integer('facility_id')->nullable() ;
               $table->integer('financial_year')->nullable() ;
               $table->integer('partner_id')->nullable() ;
               $table->integer('hts_tst_below_15_female')->nullable() ;
               $table->integer('hts_tst_below_15_male')->nullable() ;
               $table->integer('hts_tst_above_15_female')->nullable() ;
               $table->integer('hts_tst_above_15_male')->nullable() ;
               $table->integer('hts_tst_pos_below_15_female')->nullable() ;
               $table->integer('hts_tst_pos_below_15_male')->nullable() ;
               $table->integer('hts_tst_pos_above_15_female')->nullable() ;
               $table->integer('hts_tst_pos_above_15_male')->nullable() ;
               $table->integer('tx_new_below_15_female')->nullable() ;
               $table->integer('tx_new_below_15_male')->nullable() ;
               $table->integer('tx_new_above_15_female')->nullable() ;
               $table->integer('tx_new_above_15_male')->nullable() ;
               $table->integer('vmmc_circ_below_15_male')->nullable() ;
               $table->integer('vmmc_circ_above_15_male')->nullable() ;
               $table->integer('prep_new_below_15_female')->nullable() ;
               $table->integer('prep_new_below_15_male')->nullable() ;
               $table->integer('prep_new_above_15_female')->nullable() ;
               $table->integer('prep_new_above_15_male')->nullable() ;
               $table->integer('tx_curr_below_15_female')->nullable() ;
               $table->integer('tx_curr_below_15_male')->nullable() ;
               $table->integer('tx_curr_above_15_female')->nullable() ;
               $table->integer('tx_curr_above_15_male')->nullable() ;  
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
        Schema::dropIfExists('t_facility_hfr_target');
    }
}
