<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountyEtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('county_etl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id');
            $table->text('varchar');
            $table->text('CountyDHISCode');
            $table->text('rawcode');
            $table->text('CountyCoordinates');
            $table->integer('pmtctneed1617');
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
        Schema::dropIfExists('county_etl');
    }
}
