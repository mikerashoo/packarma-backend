<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToCustomerEnquiryTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created on : 20/06/2022
     * uses: Create new column to customer enquiry table
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_enquiries', function (Blueprint $table) {
            $table->string('city_name')->nullable()->after('city_id');
            $table->string('flat')->nullable()->after('city_name');
            $table->string('area')->nullable()->after('flat');
            $table->string('land_mark')->nullable()->after('area');
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
            $table->dropColumn('flat');
            $table->dropColumn('area');
            $table->dropColumn('land_mark');
        });
    }
}
