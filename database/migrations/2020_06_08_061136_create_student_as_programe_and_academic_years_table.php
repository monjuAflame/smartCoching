<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAsProgrameAndAcademicYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_as_programe_and_academic_years', function (Blueprint $table) {
            $table->bigIncrements('s_p_a_id');
            $table->unsignedBigInteger('academic_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('student_id');

            $table->foreign('academic_id')->references('academic_id')->on('academics');
            $table->foreign('level_id')->references('level_id')->on('levels');
            $table->foreign('student_id')->references('student_id')->on('students');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_as_programe_and_academic_years');
    }
}
