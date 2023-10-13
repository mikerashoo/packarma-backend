<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductQuantityColumnToVendorQuotationTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 01/06/2022
     * Uses : Changing product quantity default value
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            $table->integer('product_quantity')->default(1)->change();
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
