<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToFighters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fighters', function (Blueprint $table) {
            $table->integer('weight_class_id')->after('weight_lbs')->unsigned();
            $table->string('fighter_image_name')->after('weight_class_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fighters', function (Blueprint $table) {
            $table->dropColumn('weight_class_id');
            $table->dropColumn('fighter_image_name');
        });
    }
}
