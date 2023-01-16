<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorWarehouseIdColumnToOrderTable extends Migration
{
    /**
     * Created by : Pradyumn Dwivedi
     * Created at : 06/06/2022
     * Uses : Add new column to table
     *
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('vendor_warehouse_id')->default(0)->after('vendor_id');
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
            $table->dropColumn('user_address_id');
        });
    }
}
