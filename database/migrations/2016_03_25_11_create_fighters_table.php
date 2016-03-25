<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFightersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fighters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->integer('wins');
            $table->integer('loses');
            $table->integer('draws');
            $table->string('gender', 1);
            $table->integer('height_ft');
            $table->integer('height_in');
            $table->integer('weight_lbs');
            $table->tinyInteger('status');
            $table->integer('nationality_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fighters');
    }
}
