<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryChargesColumnToOrdersTable extends Migration
{
    /**
     * Created by : Pradyumn Dwivedi
     * Created at : 21-Sept-2022
     * Uses : Add delivery charges column to table
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_type',50)->default('none')->after('freight_amount')->comment('none|admin|delivery_boy|vendor|third_party|customer');
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
            //
        });
    }
}
