<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVendorPriceAndCommissionAmtToVendorQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            $table->decimal('vendor_price', $precision = 8, $scale = 2)->comment('Per Kg')->change();
            $table->decimal('commission_amt', $precision = 8, $scale = 2)->comment('Per Kg')->change();
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
