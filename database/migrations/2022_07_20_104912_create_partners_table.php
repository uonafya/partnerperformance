<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('mysql')->hasTable('partners_etl')) return;

        Schema::connection('mysql')->create('partners_etl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id')->nullable();
            $table->text('name')->nullable();
            $table->text('partnerDHISCode')->nullable();
            $table->text('mech_id')->nullable();
            $table->text('funding_agency')->nullable();
            $table->tinyInteger('funding_agency_id')->unsigned()->nullable();
            $table->text('logo')->nullable();
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
        Schema::dropIfExists('partners_etl');
    }
}
