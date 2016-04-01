<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePivotTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('pick_to_power_up', 'pick_power_up');
        Schema::rename('fight_to_fighter', 'fight_fighter');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('pick_power_up', 'pick_to_power_up');
        Schema::rename('fight_fighter', 'fight_to_fighter');
    }
}
