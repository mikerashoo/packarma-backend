<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToOrderTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 16/05/2022
     * Uses : Creating new columns in Order table.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('mrp', $precision = 8, $scale = 2)->default(0.00)->after('currency_id');
            $table->decimal('gst_amount', $precision = 8, $scale = 2)->default(0.00)->after('sub_total');
            $table->string('gst_type')->nullable()->comment('cgst+sgst|igst')->after('gst_amount');
            $table->decimal('gst_percentage', $precision = 8, $scale = 2)->default(0.00)->after('gst_type');
            $table->decimal('freight_amount', $precision = 8, $scale = 2)->default(0.00)->after('gst_percentage');
            $table->decimal('commission', $precision = 8, $scale = 2)->default(0.00)->after('grand_total');
            $table->decimal('vendor_amount', $precision = 8, $scale = 2)->default(0.00)->after('commission');
            $table->longText('billing_details')->nullable()->comment('Json Data')->after('shipping_details');
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
            $table->dropColumn('mrp');
            $table->dropColumn('gst_amount');
            $table->dropColumn('gst_type');
            $table->dropColumn('gst_percentage');
            $table->dropColumn('freight_amount');
            $table->dropColumn('commission');
            $table->dropColumn('vendor_amount');
            $table->dropColumn('billing_details  ');
        });
    }
}
