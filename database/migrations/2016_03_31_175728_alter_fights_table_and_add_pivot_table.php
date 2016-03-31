<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFightsTableAndAddPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fights', function($table) {
            $table->dropForeign('fights_fighterA_id_foreign');
            $table->dropForeign('fights_fighterB_id_foreign');
        });

        Schema::table('fights', function (Blueprint $table) {
            $table->dropColumn('fighterA_id');
            $table->dropColumn('fighterB_id');
        });

        Schema::create('fight_to_fighter', function($table) {
            $table->integer('fight_id')->unsigned();
            $table->integer('fighter_id')->unsigned();
        });

        Schema::table('fight_to_fighter', function($table) {
            $table->foreign('fight_id')->references('id')->on('fights');
            $table->foreign('fighter_id')->references('id')->on('fighters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fights', function (Blueprint $table) {
            $table->integer('fighterA')->unsigned();
            $table->integer('fighterB')->unsigned();
        });

        Schema::drop('fight_to_fighter');
    }
}
