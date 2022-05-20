<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_devices', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->default(0);
            $table->string('imei_no', 255)->nullable()->comment('device_id');
            $table->text('refresh_token')->nullable();
            $table->longText('remember_token')->nullable();
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
        Schema::dropIfExists('vendor_devices');
    }
}
