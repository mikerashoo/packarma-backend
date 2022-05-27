<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerEnquiryIdColumnsToOrderTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 26/05/2022
     * Uses : Adding new column to order table
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('customer_enquiry_id')->default(0)->after('vendor_id');
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
            $table->dropColumn('customer_enquiry_id ');
        });
    }
}
