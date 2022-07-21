<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::connection('mysql_etl')->hasTable('counties')) return;

        Schema::connection('mysql_etl')->create('counties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id')->nullable();
            $table->string('name')->nullable(); 
            $table->string('CountyDHISCode')->nullable(); 
            $table->string('CountyMFLCode')->nullable(); 
            $table->string('rawcode')->nullable(); 
            $table->string('CountyCoordinates')->nullable(); 
            $table->string('pmtctneed1617')->nullable();  
            $table->string('letter')->nullable();            
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
        Schema::dropIfExists('counties');
    }
}
