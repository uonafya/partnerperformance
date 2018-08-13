<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('data_sets', function (Blueprint $table) {
        //     $table->tinyIncrements('id');
        //     $table->string('name')->nullable();
        //     $table->string('code', 30)->nullable();
        //     $table->string('dhis', 30)->nullable();
        //     $table->string('category_dhis', 30)->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('data_sets');
    }
}
