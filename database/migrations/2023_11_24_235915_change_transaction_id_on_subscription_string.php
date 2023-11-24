<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTransactionIdOnSubscriptionString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_subscription_payments', function (Blueprint $table) {
            $table->string('transaction_id')->default("")->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_subscription_payments', function (Blueprint $table) {
            $table->integer('transaction_id')->default(0)->change();

        });
    }
}
