<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->integer('sub_category_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('shelf_life')->default(0);
            $table->decimal('product_weight', $precision = 8, $scale = 3);
            $table->integer('measurement_unit_id')->default(0);
            $table->integer('storage_condition_id')->default(0);
            $table->integer('packaging_machine_id')->default(0);
            $table->integer('product_form_id')->default(0);
            $table->integer('packing_type_id')->default(0);
            $table->integer('packaging_treatment_id')->default(0);
            $table->integer('country_id')->default(0);
            $table->integer('currency_id')->default(0); 
            $table->decimal('sub_total', $precision = 8, $scale = 3);
            $table->decimal('grand_total', $precision = 8, $scale = 3);
            $table->decimal('customer_pending_payment', $precision = 8, $scale = 3);
            $table->string('customer_payment_status', 255)->default('pending')->comment('pending|semi_paid|fully_paid');
            $table->decimal('vendor_pending_payment', $precision = 8, $scale = 3);
            $table->string('vendor_payment_status', 255)->default('pending')->comment('pending|semi_paid|fully_paid');
            $table->longText('order_details')->nullable()->comment('Json Data');
            $table->longText('product_details')->nullable()->comment('Json Data');
            $table->longText('shipping_details')->nullable()->comment('Json Data');
            $table->string('order_delivery_status', 255)->default('pending')->comment('pending|processing|out_for_delivery|delivered');
            $table->datetime('processing_datetime')->nullable();
            $table->datetime('out_for_delivery_datetime')->nullable();
            $table->datetime('delivery_datetime')->nullable();
            $table->integer('delivered_by')->default(0);
            $table->text('user_choice')->nullable()->comment('Json Data');
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
        Schema::dropIfExists('orders');
    }
}
