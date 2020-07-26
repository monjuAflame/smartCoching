<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_expense', function (Blueprint $table) {
            $table->bigIncrements('d_e_id');
            $table->unsignedBigInteger('expense_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('e_type_id');
            $table->datetime('date');
            $table->string('description', 80);
            $table->float('amount',8,2);
            $table->foreign('expense_id')->references('expense_id')->on('expenses');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('e_type_id')->references('e_type_id')->on('expense_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_expense');
    }
}
