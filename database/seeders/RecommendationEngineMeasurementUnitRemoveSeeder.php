<?php

namespace Database\Seeders;

use App\Models\RecommendationEngine;
use Illuminate\Database\Seeder;

class RecommendationEngineMeasurementUnitRemoveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recommendation_engines = RecommendationEngine::all();
        foreach($recommendation_engines as $value){
            $value->update(['measurement_unit_id' => 0]);
        }
    }
}
