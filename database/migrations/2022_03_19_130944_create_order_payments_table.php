<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->string('payment_mode', 255)->default('online')->comment('online_payment');
            $table->decimal('amount', $precision = 8, $scale = 3);
            $table->string('payment_status', 255)->default('pending')->comment('pending|semi_paid|fully_paid');
            $table->date('transaction_date')->nullable();
            $table->longText('remark')->nullable();
            $table->text('gateway_id')->nullable();
            $table->text('gateway_key')->nullable();
            $table->string('order_payment_image', 100)->nullable();
            $table->string('order_payment_thumb_image', 100)->nullable();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
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
        Schema::dropIfExists('order_payments');
    }
}
