<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryChargesColumnToVendorQuotationsTable extends Migration
{
    /**
     * Created by : Pradyumn Dwivedi
     * Created at : 21-Sept-2022
     * Uses : Add delivery charges column to table
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            $table->decimal('delivery_charges', $precision = 15, $scale = 2)->default(0.00)->after('freight_amount');
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
