<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_details', function (Blueprint $table) {
            $table->integer('user_id')->index()->unsigned();
            $table->string('stripe_id');
            $table->integer('credit_card_types')->unsigned();
            $table->string('cc_digits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stripe_details');
    }
}
