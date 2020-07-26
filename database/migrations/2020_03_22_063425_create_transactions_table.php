<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('transact_id');
            $table->datetime('transact_date');
            $table->unsignedBigInteger('fee_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('s_fee_id');
            $table->float('paid',8,2);
            $table->string('remark',50)->nullable();
            $table->string('description',200)->nullable();
            $table->foreign('fee_id')->references('fee_id')->on('fees');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->foreign('s_fee_id')->references('s_fee_id')->on('student_fees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
