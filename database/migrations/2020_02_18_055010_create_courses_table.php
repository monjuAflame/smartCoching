<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('course_id');
            $table->unsignedBigInteger('academic_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('shift_id');
            $table->unsignedBigInteger('time_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('batch_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('description', 50);
            $table->boolean('active');
            $table->foreign('academic_id')->references('academic_id')->on('academics');
            $table->foreign('level_id')->references('level_id')->on('levels');
            $table->foreign('shift_id')->references('shift_id')->on('shifts');
            $table->foreign('time_id')->references('time_id')->on('times');
            $table->foreign('group_id')->references('group_id')->on('groups');
            $table->foreign('batch_id')->references('batch_id')->on('batches');
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
        Schema::dropIfExists('courses');
    }
}
