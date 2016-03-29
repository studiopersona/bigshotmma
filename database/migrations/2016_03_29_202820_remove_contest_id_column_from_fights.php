<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveContestIdColumnFromFights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fights', function (Blueprint $table) {
            $table->dropForeign('fights_contest_id_foreign');
        });

        Schema::table('fights', function (Blueprint $table) {
            $table->dropColumn('contest_id');
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
            $table->integer('contest_id');
        });

        Schema::table('fights', function (Blueprint $table) {
            $table->foreign('contest_id')->references('id')->on('contests');
        });
    }
}
