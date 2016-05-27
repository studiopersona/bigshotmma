<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestUserBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_user_balance', function (Blueprint $table) {
            $table->integer('contest_id')->unsigned();
            $table->integer('user_balance_id')->unsigned();
            $table->tinyInteger('is_entry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contest_user_balance');
    }
}
