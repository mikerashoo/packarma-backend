<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('subscription_id')->default(0);
            $table->decimal('amount', $precision = 8, $scale = 3);
            $table->string('subscription_type', 255)->default('monthly')->comment('monthly|quarterly|semi_yearly|yearly');
            $table->string('payment_mode', 255)->default('online')->comment('online_payment');
            $table->integer('payment_reference')->default(0);
            $table->string('payment_unique_id')->default(0);
            $table->longText('payment_details')->nullable()->comment('Json Data');
            $table->string('payment_status', 255)->default('pending')->comment('pending|paid|failed');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('user_subscription_payments');
    }
}
