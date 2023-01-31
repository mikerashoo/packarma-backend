<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\RecommendationEngine;
use Illuminate\Database\Seeder;

class RecommendationEngineAddMeasurementUnitSeeder extends Seeder
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
            $measurement_unit_id = Product::find($value->product_id);
            $value->update(['measurement_unit_id' => $measurement_unit_id['unit_id']]);
        }
        
    }
}
