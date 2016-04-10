<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveForeignKeyPowerupIdOnFightResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fight_results', function (Blueprint $table) {
            $table->dropForeign(['power_up_id']);
        });

        Schema::table('fight_results', function (Blueprint $table) {
            $table->dropColumn('power_up_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fight_results', function (Blueprint $table) {
            $table->integer('power_up_id')->unsigned();
        });

        Schema::table('fight_results', function (Blueprint $table) {
            $table->foreign('power_up_id')->references('id')->on('power_ups');
        });
    }
}
