<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVendorPriceAndCommissionAmtToVendorQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            $table->decimal('vendor_price', $precision = 15, $scale = 2)->comment('Per Kg')->change();
            $table->decimal('commission_amt', $precision = 15, $scale = 2)->comment('Per Kg')->change();
            $table->decimal('mrp', $precision = 15, $scale = 2)->change();
            $table->decimal('sub_total', $precision = 15, $scale = 2)->change(); 
            $table->decimal('gst_amount', $precision = 15, $scale = 2)->change();
            $table->decimal('gst_percentage', $precision = 15, $scale = 2)->change();
            $table->decimal('freight_amount', $precision = 15, $scale = 2)->change();
            $table->decimal('total_amount', $precision = 15, $scale = 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            //
        });
    }
}
