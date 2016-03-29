<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPowerUps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('power_ups', function (Blueprint $table) {
            $table->text('power_up_description')->after('power_up_name');
            $table->string('power_up_color')->after('penalty_points');
            $table->string('power_up_image_name')->after('power_up_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('power_ups', function (Blueprint $table) {
            $table->dropColumn('power_up_description');
            $table->dropColumn('power_up_color');
            $table->dropColumn('power_up_image_name');
        });
    }
}
