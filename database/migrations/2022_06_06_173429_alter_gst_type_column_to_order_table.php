<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGstTypeColumnToOrderTable extends Migration
{
    /**
     * created By : Pradyumn Dwivedi
     * Creayed at : 06/06/2022
     * Uses : alter gst type column
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('gst_type')->default('not_applicable')->comment('not_applicable|cgst+sgst|igst')->change();
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
