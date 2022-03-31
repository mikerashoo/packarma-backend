<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('warehouse_name', 100)->nullable();
            $table->integer('city_id')->default(0);
            $table->integer('state_id')->default(0);
            $table->integer('country_id')->default(0);
            $table->text('address')->nullable();
            $table->integer('pincode')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_warehouses');
    }
}
