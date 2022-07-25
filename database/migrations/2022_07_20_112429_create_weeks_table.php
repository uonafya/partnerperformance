<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(Schema::connection('mysql_etl')->hasTable('weeks')) return;

        Schema::connection('mysql_etl')->create('weeks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('week_number')->nullable(); 
            $table->string('start_date')->nullable(); 
            $table->string('end_date')->nullable(); 
            $table->string('year')->nullable(); 
            $table->string('month')->nullable(); 
            $table->string('financial_year')->nullable(); 
            $table->string('quarter')->nullable(); 
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
        Schema::dropIfExists('weeks');
    }
}
