<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPowerUpIdColumnToPicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('picks', function (Blueprint $table) {
            $table->integer('power_up_id')->unsigned()->nullable()->after('finish_id');
        });

        Schema::table('picks', function (Blueprint $table) {
            $table->foreign('power_up_id')->references('id')->on('power_ups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('picks', function (Blueprint $table) {
            $table->dropColumn('power_up_id');
        });
    }
}
