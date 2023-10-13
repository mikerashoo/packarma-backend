<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToVendorWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_warehouses', function (Blueprint $table) {
            $table->string('flat', 255)->nullable()->after('pincode');
            $table->string('area', 255)->nullable()->after('flat');
            $table->string('land_mark', 255)->nullable()->after('area');
            $table->string('city_name', 255)->nullable()->after('land_mark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_warehouses', function (Blueprint $table) {
            //
        });
    }
}
