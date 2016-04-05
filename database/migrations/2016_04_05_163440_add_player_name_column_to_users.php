<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlayerNameColumnToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('player_name')->after('password');
            $table->string('firstname')->nullable()->change();
            $table->string('lastname')->nullable()->change();
            $table->string('address1')->nullable()->change();
            $table->string('address2')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('zipcode')->nullable()->change();
            $table->string('country')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('balance')->nullable()->change();
            $table->integer('user_role_id')->unsigned()->default(1)->change();
            $table->integer('balance')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('player_name');
        });
    }
}
