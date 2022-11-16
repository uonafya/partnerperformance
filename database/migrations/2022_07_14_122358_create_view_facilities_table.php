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
        if(!Schema::connection('mysql_etl')->hasTable('view_facilitys')){
            Schema::connection('mysql_etl')->create('view_facilitys', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('old_id');
                $table->string('longitude')->nullable();
                $table->string('latitude')->nullable();
                $table->string('DHISCode')->nullable();
                $table->integer('facilitycode')->nullable();
                $table->string('name')->nullable();
                $table->string('ward_id')->nullable();
                $table->string('wardname')->nullable();
                $table->string('WardDHISCode')->nullable();
                $table->string('district')->nullable();
                $table->string('subcounty')->nullable();
                $table->string('parteners')->nullable();
                $table->string('partnersname')->nullable();
                $table->string('partner2')->nullable();
                $table->string('start_of_support')->nullable()->nullable();
                $table->string('end_of_support')->nullable()->nullable();
                $table->string('funding_agency_id')->nullable();
                $table->string('funding_agency')->nullable();
                $table->string('county_id')->nullable();
                $table->string('countyname')->nullable();
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
        Schema::dropIfExists('view_facilitys');
    }
}
