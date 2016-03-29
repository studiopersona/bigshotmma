<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageNameColumnToContestTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contest_types', function (Blueprint $table) {
            $table->string('image_name')->after('contest_type_rules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contest_types', function (Blueprint $table) {
            $table->dropColumn('image_name');
        });
    }
}
