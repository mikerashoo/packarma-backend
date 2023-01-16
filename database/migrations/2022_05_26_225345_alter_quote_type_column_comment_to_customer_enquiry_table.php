<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterQuoteTypeColumnCommentToCustomerEnquiryTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 26/05/2022
     * Uses : Adding auto_reject comment value to quote type in customer enquiry table 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_enquiries', function (Blueprint $table) {
            $table->string('quote_type', 255)->default('enquired')->comment('enquired|map_to_vendor|accept_cust|closed|auto_reject')->change();
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
            $table->dropColumn('quote_type');
        });
    }
}
