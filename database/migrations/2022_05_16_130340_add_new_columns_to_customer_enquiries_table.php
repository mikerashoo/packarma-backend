<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToCustomerEnquiriesTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 16/05/2022
     * Uses : Creating new columns in customer enquiry table and deleting column from customer enquiry.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_enquiries', function (Blueprint $table) {
            $table->integer('user_address_id')->default(0)->after('packaging_treatment_id');
            $table->dropColumn('address');
            $table->dropColumn('country_id');
            $table->dropColumn('city_id');
            $table->dropColumn('state_id');
            $table->dropColumn('pincode');
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
            $table->dropColumn('user_address_id');
            $table->dropColumn('order_id');
        });
    }
}
