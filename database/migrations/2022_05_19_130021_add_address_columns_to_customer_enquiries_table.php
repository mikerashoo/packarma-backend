<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressColumnsToCustomerEnquiriesTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 19/05/2022
     * Uses : adding columns in customer enquiry table.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_enquiries', function (Blueprint $table) {
            $table->integer('country_id')->default(0)->after('user_address_id');
            $table->integer('state_id')->default(0)->after('country_id');
            $table->integer('city_id')->default(0)->after('state_id');
            $table->text('address')->nullable()->after('city_id');
            $table->string('pincode',15)->nullable()->after('address');
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
            $table->dropColumn('country_id');
            $table->dropColumn('state_id');
            $table->dropColumn('city_id');
            $table->dropColumn('address');
            $table->dropColumn('pincode');
        });
    }
}
