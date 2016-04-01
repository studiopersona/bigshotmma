<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MiscellaneousTableChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finishes', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->change();
        });

        Schema::table('contest_types', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->change();
        });

        Schema::table('fighters', function (Blueprint $table) {
            $table->integer('status')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
