<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('user_role_id')->references('id')->on('user_roles');
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events');
        });

        Schema::table('referrals', function (Blueprint $table) {
            $table->foreign('discount_type_id')->references('id')->on('discount_types');
        });

        Schema::table('contests', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('contest_type_id')->references('id')->on('contest_types');
        });

        Schema::table('fighters', function (Blueprint $table) {
            $table->foreign('nationality_id')->references('id')->on('nationalities');
        });

        Schema::table('fights', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('contest_id')->references('id')->on('contests');
            $table->foreign('fighterA_id')->references('id')->on('fighters');
            $table->foreign('fighterB_id')->references('id')->on('fighters');
        });


        Schema::table('picks', function (Blueprint $table) {
            $table->foreign('contest_id')->references('id')->on('contests');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('finish_id')->references('id')->on('finishes');
            $table->foreign('winning_fighter_id')->references('id')->on('fighters');
        });

        Schema::table('contest_participants', function (Blueprint $table) {
            $table->foreign('contest_id')->references('id')->on('contests');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('winners', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('fight_results', function (Blueprint $table) {
            $table->foreign('winning_fighter_id')->references('id')->on('fighters');
            $table->foreign('finish_id')->references('id')->on('finishes');
            $table->foreign('fight_id')->references('id')->on('fights');
        });

        Schema::table('picks_to_power_ups', function (Blueprint $table) {
            $table->foreign('pick_id')->references('id')->on('picks');
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
        //
    }
}
