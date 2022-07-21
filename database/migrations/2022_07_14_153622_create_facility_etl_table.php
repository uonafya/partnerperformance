<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityEtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('mysql')->hasTable('facility_etl'))  return;

        Schema::connection('mysql')->create('facility_etl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id');
            $table->integer('facilitycode');
            $table->unsignedSmallInteger('district');
            $table->unsignedSmallInteger('subcounty_id');
            $table->unsignedSmallInteger('ward_id');
            $table->text('name');
            $table->text('new_name');
            $table->integer('lab');
            $table->integer('partner');
            $table->text('f_type');
            $table->text('DHIScode');
            $table->text('facility_uid');
            $table->tinyInteger('community');
            $table->tinyInteger('is_pns');
            $table->tinyInteger('is_viremia');
            $table->tinyInteger('is_dsd');
            $table->tinyInteger('is_otz');
            $table->tinyInteger('is_men_clinic');
            $table->tinyInteger('is_surge');
            $table->text('longitude');
            $table->text('latitude');
            $table->text('burden');
            $table->integer('artpatients');
            $table->integer('pmtctnos');
            $table->integer('Mless15');
            $table->integer('Mmore15');
            $table->integer('Fless15');
            $table->integer('Fmore15');
            $table->integer('totalartmar');
            $table->integer('totalartsep17');
            $table->integer('totalartsep15');
            $table->date('asofdate');
            $table->integer('partnerold');
            $table->integer('partner2');
            $table->integer('partner3');
            $table->integer('partner4');
            $table->integer('partner5');
            $table->integer('partner6');
            $table->text('telephone');
            $table->text('telephone2');
            $table->text('contactperson');
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
        Schema::dropIfExists('facility_etl');
    }
}
