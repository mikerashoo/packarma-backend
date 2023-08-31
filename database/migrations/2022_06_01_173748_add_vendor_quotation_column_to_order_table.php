<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorQuotationColumnToOrderTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 01/06/2022
     * Uses : Adding vendor quotation column to order table
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('vendor_quotation_id')->default(0)->after('user_id');
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
            $table->dropColumn('vendor_quotation_id ');
        });
    }
}
