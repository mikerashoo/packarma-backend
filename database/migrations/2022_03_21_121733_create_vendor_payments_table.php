<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->string('payment_status', 255)->default('pending')->comment('pending|semi_paid|fully_paid');
            $table->string('payment_mode', 255)->default('cod')->comment('cod|bank_transfer|payment_gateway|cheque|demand_draft');
            $table->decimal('amount', $precision = 8, $scale = 3)->comment('chunk payment');
            $table->longText('remark')->nullable();
            $table->date('transaction_date')->nullable();
            $table->longText('payment_details')->nullable();
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
        Schema::dropIfExists('vendor_payments');
    }
}
