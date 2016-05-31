<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveContestAndEventIdColumnsFromFightResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fight_results', function (Blueprint $table) {
            $table->dropColumn('contest_id');
            $table->dropColumn('event_id');
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
            $table->integer('contest_id')->unsigned();
            $table->integer('event_id')->unsigned();
        });
    }
}
