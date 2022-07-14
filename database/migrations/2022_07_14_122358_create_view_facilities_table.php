<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_etl')->create('view_facilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('DHISCode');
            $table->integer('facilitycode');
            $table->string('name');
            $table->string('ward_id');
            $table->string('wardname');
            $table->string('WardDHISCode');
            $table->string('district');
            $table->string('subcounty');
            $table->string('parteners');
            $table->string('partnersname');
            $table->string('partner2');
            $table->string('start_of_support')->nullable();
            $table->string('end_of_support')->nullable();
            $table->string('funding_agency_id');
            $table->string('funding_agency');
            $table->string('county_id');
            $table->string('countyname');
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
        Schema::dropIfExists('view_facilities');
    }
}
