<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_quotations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('customer_enquiry_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->integer('warehouse_id')->default(0);
            $table->decimal('vendor_price', $precision = 8, $scale = 3)->comment('Per Kg');
            $table->decimal('commission_amt', $precision = 8, $scale = 3)->comment('Per Kg');
            $table->string('enquiry_status', 255)->default('pending')->comment('pending|viewed|accept|reject|requote');
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
        Schema::dropIfExists('vendor_quotations'); 
    }
}
