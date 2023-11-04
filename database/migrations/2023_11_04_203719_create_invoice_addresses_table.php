<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('mobile_no', 20);
            $table->text('billing_address');

            $table->foreignId('user_id')->references('id')
                ->on('users')->cascadeOnDelete();

            $table->foreignId('country_id')->references('id')
                ->on('countries')->default(1)->cascadeOnDelete();
            $table->foreignId('city_id')->references('id')
                ->on('cities')->cascadeOnDelete();

            $table->foreignId('state_id')->references('id')
                ->on('states')->cascadeOnDelete();
            $table->string('pincode', 15)->nullable();

            $table->string('gstin', 15)->nullable();
            $table->string('email', 100)->nullable();

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
        Schema::dropIfExists('invoice_addresses');
    }
}
