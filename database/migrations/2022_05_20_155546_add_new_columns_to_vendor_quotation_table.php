<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToVendorQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            $table->integer('product_quantity')->default(0)->after('commission_amt');
            $table->decimal('mrp', $precision = 8, $scale = 2)->default(0.00)->after('product_quantity');
            $table->decimal('sub_total', $precision = 8, $scale = 2)->default(0.00)->after('mrp');
            $table->decimal('gst_amount', $precision = 8, $scale = 2)->default(0.00)->after('sub_total');
            $table->string('gst_type')->default('not_applicable')->comment('not_applicable|cgst+sgst|igst')->after('gst_amount');
            $table->decimal('gst_percentage', $precision = 8, $scale = 2)->default(0.00)->after('gst_type');
            $table->decimal('freight_amount', $precision = 8, $scale = 2)->default(0.00)->after('gst_percentage');
            $table->decimal('total_amount', $precision = 8, $scale = 2)->default(0.00)->after('freight_amount');
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
            $table->dropColumn('product_quantity');
            $table->dropColumn('mrp');
            $table->dropColumn('sub_total');
            $table->dropColumn('gst_amount');
            $table->dropColumn('gst_type');
            $table->dropColumn('gst_percentage');
            $table->dropColumn('freight_amount');
            $table->dropColumn('total_amount');
        });
    }
}
