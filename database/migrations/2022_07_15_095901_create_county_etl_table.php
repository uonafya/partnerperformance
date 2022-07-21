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
        if (!Schema::connection('mysql')->hasTable('county_etl')) {
            Schema::connection('mysql')->create('county_etl', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('old_id');
                $table->text('name')->nullable();
                $table->text('DHISCode')->nullable();
                $table->text('MFLCode')->nullable();
                $table->string('rawcode')->nullable();
                $table->text('Coordinates')->nullable();
                $table->string('pmtctneed1617')->nullable();
                $table->string('letter')->nullable();
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
        Schema::dropIfExists('county_etl');
    }
}
