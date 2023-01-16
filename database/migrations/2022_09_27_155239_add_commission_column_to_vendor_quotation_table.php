<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionColumnToVendorQuotationTable extends Migration
{
    /**
     * created By : Pradyumn Dwivedi
     * Created at : 27-Sept-2022
     * Uses : Add new column to vendor quotation table
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            $table->decimal('vendor_amount', $precision = 15, $scale = 2)->default(0.00)->after('total_amount');
            $table->decimal('commission', $precision = 15, $scale = 2)->default(0.00)->after('vendor_amount');
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
