<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePaymentReferenceAndPaymentUniqueIdFromUserSubscriptionPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_subscription_payments', function (Blueprint $table) {
            $table->dropColumn('payment_reference');
            $table->dropColumn('payment_unique_id');
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
            //
        });
    }
}
