<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 30-mar-2022
     * uses : to to get data of sub category in product 
     */
    public function sub_category()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 30-mar-2022
     * uses : to to get data of category in product 
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 30-mar-2022
     * uses : to to get data of product form in product 
     */
    public function product_form()
    {
        return $this->belongsTo('App\Models\ProductForm');
    }
    public function units()
    {
        return $this->hasOne('App\Models\MeasurementUnit');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 30-mar-2022
     * uses : to to get data of product treatment in product 
     */
    public function packaging_treatment()
    {
        return $this->belongsTo('App\Models\PackagingTreatment');
    }

    public function recommendation_engine()
    {
        return $this->belongsTo('App\Models\RecommendationEngine', 'id', 'product_id');
    }

    /**
     * Developed By : Maaz Ansari
     * Created On : 01-Aug-2022
     * uses : to change name to Camel Casing
     */

    // mutators start
    public function setProductNameAttribute($value)
    {
        $this->attributes['product_name'] = ucwords(strtolower($value));
    }

    public function setProductDescriptionAttribute($value)
    {
        $this->attributes['product_description'] = ucwords(strtolower($value));
    }
    // mutators end
}
