<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUserSubscriptionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_subscription_payments', function (Blueprint $table) {
            $table->date('transaction_date')->nullable()->after('payment_mode');
            $table->text('gateway_id')->nullable()->after('transaction_date');
            $table->text('gateway_key')->nullable()->after('gateway_id');
            $table->string('call_from', 255)->default('android')->comment('website|android|ios|mobile|facebook|twitter|youtube|social|telecaller|others')->after('gateway_key');
            $table->string('ip_address', 255)->nullable()->after('call_from');
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
