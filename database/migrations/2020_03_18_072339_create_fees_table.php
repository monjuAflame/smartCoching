<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->bigIncrements('fee_id');
            $table->unsignedBigInteger('academic_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('fee_type_id');
            $table->string('fee_heading',100)->nullable();
            $table->float('amount',8,2);
            $table->foreign('academic_id')->references('academic_id')->on('academics');
            $table->foreign('level_id')->references('level_id')->on('levels');
            $table->foreign('fee_type_id')->references('fee_type_id')->on('feetypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fees');
    }
}
