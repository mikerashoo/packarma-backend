<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPackagingMaterialIdColumnsToCustomerEnquiryTable extends Migration
{
    /**
     * Created By : Pradyumn dwivedi
     * Created at : 26/05/2022
     * Uses : Adding new column in customer enquiry table
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_enquiries', function (Blueprint $table) {
            $table->integer('packaging_material_id')->default(0)->after('recommendation_engine_id');
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
            $table->dropColumn('packaging_material_id ');
        });
    }
}
