<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOddsColumnToFightFighter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fight_fighter', function (Blueprint $table) {
            $table->integer('odds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fight_fighter', function (Blueprint $table) {
            $table->dropColumn('odds');
        });
    }
}
