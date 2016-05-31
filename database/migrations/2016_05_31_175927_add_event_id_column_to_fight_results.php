<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventIdColumnToFightResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fight_results', function (Blueprint $table) {
            $table->integer('event_id')->index()->unsigned()->after('contest_id');
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
            $table->dropColumn('event_id');
        });
    }
}
