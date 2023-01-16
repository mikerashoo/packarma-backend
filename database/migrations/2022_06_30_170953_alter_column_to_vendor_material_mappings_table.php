<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnToVendorMaterialMappingsTable extends Migration
{
    /**
     * Created by : Pradyumn Dwivedi
     * Created at : 30-June-2022
     * Uses : To alter column in table
     * 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_material_mappings', function (Blueprint $table) {
            $table->decimal('min_amt_profit', $precision = 15, $scale = 2)->default(0.00)->comment('Per Kg')->change();
            $table->decimal('min_stock_qty', $precision = 15, $scale = 2)->default(0.00)->change();
            $table->decimal('vendor_price', $precision = 15, $scale = 2)->default(0.00)->comment('Per Kg')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_material_mappings', function (Blueprint $table) {
            //
        });
    }
}
