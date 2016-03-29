<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPointColumnToFinishes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finishes', function (Blueprint $table) {
            $table->integer('points')->after('finish_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finishes', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
}
