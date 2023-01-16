<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdColumnsToVendorQuotationTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created on : 26/05/2022
     * Uses : Adding order id to vendor quotation table
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            $table->integer('order_id')->default(0)->after('customer_enquiry_id');
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
            $table->dropColumn('order_id');
        });
    }
}
