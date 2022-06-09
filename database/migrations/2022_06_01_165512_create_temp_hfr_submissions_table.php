<?php

use \App\HfrSubmission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempHfrSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $columns = HfrSubmission::columns();
        Schema::create('temp_hfr_submissions', function (Blueprint $table) use ($columns) {
            $table->bigIncrements('id');
            $table->tinyInteger('week_id');
            $table->integer('facility');

            foreach($columns as $column) {
                $table->tinyInteger($column['column_name'])->nullable();
            }

            $table->date('dateupdated');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_hfr_submissions');
    }
}
