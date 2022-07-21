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
        if (!Schema::connection('mysql')->hasTable('facility_etl')) {
            Schema::connection('mysql')->create('facility_etl', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('old_id');
                $table->text('name')->nullable();
                $table->text('new_name')->nullable();
                $table->integer('facilitycode')->nullable();
                $table->unsignedSmallInteger('district')->nullable();
                $table->unsignedSmallInteger('subcounty_id')->nullable();
                $table->unsignedSmallInteger('ward_id')->nullable();
                $table->integer('lab')->nullable();
                $table->integer('partner')->nullable();
                $table->text('facility_type')->nullable();
                $table->text('DHIS_Code')->nullable();
                $table->text('facility_uid')->nullable();
                $table->tinyInteger('community')->nullable();
                $table->tinyInteger('is_pns')->nullable();
                $table->tinyInteger('is_viremia')->nullable();
                $table->tinyInteger('is_dsd')->nullable();
                $table->tinyInteger('is_otz')->nullable();
                $table->tinyInteger('is_men_clinic')->nullable();
                $table->tinyInteger('is_surge')->nullable();
                $table->text('longitude')->nullable();
                $table->text('latitude')->nullable();
                $table->text('burden')->nullable();
                $table->integer('art_patients')->nullable();
                $table->integer('pmtctnos')->nullable();
                $table->integer('Mless15')->nullable();
                $table->integer('Mmore15')->nullable();
                $table->integer('Fless15')->nullable();
                $table->integer('Fmore15')->nullable();
                $table->integer('total_art_Mar')->nullable();
                $table->integer('total_art_Sep17')->nullable();
                $table->integer('total_art_Sep15')->nullable();
                $table->date('asofdate')->nullable();
                $table->integer('partnerold')->nullable();
                $table->integer('partner2')->nullable();
                $table->integer('partner3')->nullable();
                $table->integer('partner4')->nullable();
                $table->integer('partner5')->nullable();
                $table->integer('partner6')->nullable();
                $table->text('telephone')->nullable();
                $table->text('telephone2')->nullable();
                $table->text('contact_person')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('facility_etl');
    }
}
