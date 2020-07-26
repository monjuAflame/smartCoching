<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_messages', function (Blueprint $table) {
            $table->bigIncrements('message_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('message');
            $table->longText('mobiles');
            $table->datetime('send_date');

            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_messages');
    }
}
