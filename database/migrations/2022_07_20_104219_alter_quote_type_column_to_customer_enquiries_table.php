<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterQuoteTypeColumnToCustomerEnquiriesTable extends Migration
{
    /**
     * Created by : Pradyumn Dwivedi
     * Created at : 20-july-2022
     * Uses : To alter column in table
     * 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_enquiries', function (Blueprint $table) {
            $table->string('quote_type', 255)->default('enquired')->comment('enquired|map_to_vendor|accept_cust|order|closed|auto_reject')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_enquiries', function (Blueprint $table) {
            //
        });
    }
}
