<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFighterIdToFightResultPowerUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fight_result_power_up', function (Blueprint $table) {
            $table->integer('fighter_id')->index()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fight_result_power_up', function (Blueprint $table) {
            $table->dropColumn('fighter_id');
        });
    }
}
