<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorMaterialMapping extends Model
{
    use HasFactory;
    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 01-april-2022
     * uses : to get vendor in vendor material map table
     */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 01-april-2022
     * uses : to get packaging material in vendor material map table
     */
    public function packaging_material()
    {
        return $this->belongsTo('App\Models\PackagingMaterial');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 01-april-2022
     * uses : to get recommendation engine in vendor material map table
     */
    public function recommendation_engine()
    {
        return $this->belongsTo('App\Models\RecommendationEngine');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 01-april-2022
     * uses : to get recommendation engine in vendor material map table
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
