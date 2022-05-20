<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressColumnsToVendorQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_quotation', function (Blueprint $table) {
            $table->decimal('gst_amount', $precision = 8, $scale = 2)->default(0.00)->after('sub_total');
            $table->string('gst_type')->nullable()->comment('Cgst+Sgst|Igst')->after('gst_amount');
            $table->decimal('gst_percentage', $precision = 8, $scale = 2)->default(0.00)->after('gst_type');
            $table->decimal('total_amount', $precision = 8, $scale = 2)->default(0.00)->after('commission_amt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_quotation', function (Blueprint $table) {
            $table->dropColumn('gst_amount');
            $table->dropColumn('gst_type');
            $table->dropColumn('gst_percentage');
            $table->dropColumn('total_amount');
        });
    }
}
