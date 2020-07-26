<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->unique();
            $table->unsignedBigInteger('user_id');

            $table->string('name', 50);
            $table->string('nick_name',15);
            $table->date('dob');
            $table->string('national_id',50)->nullable()->unique();
            $table->boolean('sex');
            $table->boolean('status');
            $table->string('nationality',25)->nullable();
            $table->string('zipcode',10)->nullable();
            $table->string('passport',30)->nullable()->unique();


            $table->string('email',100)->nullable()->unique();
            $table->string('phone',15)->unique();
            
            $table->string('photo')->nullable();
            $table->boolean('active');

            $table->string('village',25)->nullable();
            $table->string('post_office',25)->nullable();
            $table->string('updistrict',25)->nullable();
            $table->string('district',25)->nullable();
            $table->string('current_address',100)->nullable();
            $table->string('description',50)->nullable();
            $table->date('dateregistered');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('students');
    }
}
