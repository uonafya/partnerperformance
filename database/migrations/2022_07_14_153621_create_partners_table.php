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
        if(Schema::connection('mysql_etl')->hasTable('partners')) return;

        Schema::connection('mysql_etl')->create('partners', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('old_id');
            $table->string('name')->nullable(); 
            $table->string('partnerDHISCode')->nullable();
            $table->string('mech_id')->nullable(); 
            $table->string('fundingagency')->nullable(); 
            $table->string('funding_agency_id')->nullable(); 
            $table->string('logo')->nullable(); 
            $table->string('country')->nullable(); 
            $table->string('flag')->nullable(); 
            $table->string('orderno')->nullable(); 
            $table->string('unknown2013')->nullable(); 
            $table->string('unknown2014')->nullable(); 
            $table->string('unknown2015')->nullable(); 
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
        Schema::dropIfExists('partners');
    }
}
