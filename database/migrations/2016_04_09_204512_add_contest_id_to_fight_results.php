<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContestIdToFightResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fight_results', function (Blueprint $table) {
            $table->integer('contest_id')->unsigned()->after('id');
        });

        Schema::table('fight_results', function (Blueprint $table) {
            $table->foreign('contest_id')->references('id')->on('contests');
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
            $table->dropForeign(['contest_id']);
        });

        Schema::table('fight_results', function (Blueprint $table) {
            $table->dropColumn('contest_id');
        });
    }
}
