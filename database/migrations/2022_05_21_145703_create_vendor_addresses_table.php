<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->string('address_name', 100)->nullable();
            $table->string('gstin', 15)->nullable();
            $table->string('type', 255)->default('shipping')->comment('shipping|billing');
            $table->string('mobile_no', 20)->nullable();
            $table->integer('city_id')->default(0);
            $table->integer('state_id')->default(0);
            $table->integer('country_id')->default(1);
            $table->text('address')->nullable();
            $table->string('pincode', 15)->nullable();
            $table->enum('status', [1, 0])->default(1);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('vendor_addresses');
    }
}
