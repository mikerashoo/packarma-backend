<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyIdColumnToVendorQuotationTable extends Migration
{
    /**
     * Created by : Pradyumn Dwivedi
     * Created at : 08/06/2022
     * Uses : Add new column to table
     *
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            $table->integer('currency_id')->default(1)->after('vendor_id');
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
            $table->dropColumn('currency_id');
        });
    }
}
