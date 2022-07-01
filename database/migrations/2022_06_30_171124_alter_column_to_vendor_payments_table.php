<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnToVendorPaymentsTable extends Migration
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
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->decimal('amount', $precision = 8, $scale = 2)->comment('chunk payment')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            //
        });
    }
}
