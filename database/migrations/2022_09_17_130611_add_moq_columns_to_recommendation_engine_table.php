<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoqColumnsToRecommendationEngineTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 17-Sept-2022
     * Uses : To add new column in recommendation engine(packaging solution) table
     * 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recommendation_engines', function (Blueprint $table) {
            $table->integer('sequence')->default(1)->after('structure_type');
            $table->decimal('min_order_quantity', $precision = 15, $scale = 2)->default(1.00)->after('approx_price');
            $table->string('min_order_quantity_unit', 50)->nullable()->after('min_order_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recommendation_engines', function (Blueprint $table) {
            //
        });
    }
}
