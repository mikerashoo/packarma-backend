<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApproxPriceColumnToPackagingMaterialTable extends Migration
{
    /**
     * Created by : Pradyumn Dwivedi
     * Created at : 30-June-2022
     * Uses : To alter column in table
     * 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packaging_materials', function (Blueprint $table) {
            $table->decimal('approx_price', $precision = 8, $scale = 2)->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packaging_materials', function (Blueprint $table) {
            //
        });
    }
}
