<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataSetElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_set_elements', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->nullable();
            $table->string('code', 30)->nullable();
            $table->string('dhis', 30)->nullable();
            $table->string('table_name', 30)->nullable();
            $table->string('column_name', 30)->nullable();
            $table->tinyInteger('data_set_id')->unsigned();
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
        Schema::dropIfExists('data_set_elements');
    }
}
