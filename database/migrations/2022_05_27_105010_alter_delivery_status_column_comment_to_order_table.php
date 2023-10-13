<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDeliveryStatusColumnCommentToOrderTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created on : 27/05/2022
     * Uses : Adding cancelled comment  in order delivey status
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_delivery_status', 255)->default('pending')->comment('pending|processing|out_for_delivery|delivered|cancelled')->change();
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
            $table->dropColumn('order_delivery_status ');
        });
    }
}
