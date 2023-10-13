<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGrandTotalColumnToOrderTable extends Migration
{
    /**
     * Created by : Pradyumn Dwivedi
     * Created at : 30-June-2022
     * Uses : To alter column in table
     * 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('product_weight', $precision = 15, $scale = 2)->default(0.00)->change();
            $table->decimal('mrp', $precision = 15, $scale = 2)->default(0.00)->change();
            $table->decimal('sub_total', $precision = 15, $scale = 2)->default(0.00)->change();
            $table->decimal('gst_amount', $precision = 15, $scale = 2)->default(0.00)->change();
            $table->decimal('gst_percentage', $precision = 15, $scale = 2)->default(0.00)->change();
            $table->decimal('freight_amount', $precision = 15, $scale = 2)->default(0.00)->change();
            $table->decimal('grand_total', $precision = 15, $scale = 2)->default(0.00)->change();
            $table->decimal('commission', $precision = 15, $scale = 2)->default(0.00)->change();
            $table->decimal('vendor_amount', $precision = 15, $scale = 2)->default(0.00)->change();
            $table->decimal('customer_pending_payment', $precision = 15, $scale = 2)->default(0.00)->change();
            $table->decimal('vendor_pending_payment', $precision = 15, $scale = 2)->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
