<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecommendationEngineMeasurementUnitRemovalSeederMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('db:seed', [
            '--class' => 'RecommendationEngineMeasurementUnitRemoveSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Artisan::call('db:seed', [
            '--class' => 'RecommendationEngineAddMeasurementUnitSeeder',
            '--force' => true
        ]);
    }
}
