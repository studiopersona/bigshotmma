<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameStripeDetailsCreditCardTypesToCreditCardTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripe_details', function (Blueprint $table) {
            $table->renameColumn('credit_card_types', 'credit_card_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripe_details', function (Blueprint $table) {
            $table->renameColumn('credit_card_type_id', 'credit_card_types');
        });
    }
}
