<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecommendationEngineIdColumnsToOrderTable extends Migration
{
    /**
     * Created By : Pradyumn dwivedi
     * Created at : 27/05/2022
     * Uses : Adding new column recommendation engine id in order table
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_id ');
        });
    }
}
