<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryInDaysColumnToVendorQuotation extends Migration
{
    /**
     * created by : Pradyumn Dwivedi
     * Created at : 27-Sept-2022
     * Uses : Add delivery in days column to vendor quotation table
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            $table->integer('delivery_in_days')->default(7);
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
