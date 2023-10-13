<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecommendationEngineColumnToCustomerEnquiriesTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 17/05/2022
     * Uses : Creating recommendation_engine columns in customer enquiry table.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_enquiries', function (Blueprint $table) {
            $table->integer('recommendation_engine_id')->default(0)->after('packaging_treatment_id');

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
            $table->dropColumn('recommendation_engine_id');
        });
    }
}
