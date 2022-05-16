<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecommendationEngine extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 13/05/2022
     * Uses : The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'vendor_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 31-mar-2022
        * uses : to get data of product  in recommendation engine
    */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 31-mar-2022
        * uses : to get data of category  in recommendation engine
    */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 31-mar-2022
        * uses : to get data of product form in recommendation engine
    */
    public function product_form()
    {
        return $this->belongsTo('App\Models\ProductForm');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 31-mar-2022
        * uses : to get data of packing type  in recommendation engine
    */
    public function packing_type()
    {
        return $this->belongsTo('App\Models\PackingType');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 31-mar-2022
        * uses : to get data of packaging machine  in recommendation engine
    */
    public function packaging_machine()
    {
        return $this->belongsTo('App\Models\PackagingMachine');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 31-mar-2022
        * uses : to get data of packaging treatment  in recommendation engine
    */
    public function packaging_treatment()
    {
        return $this->belongsTo('App\Models\PackagingTreatment');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 31-mar-2022
        * uses : to get data of packaging material  in recommendation engine
    */
    public function packaging_material()
    {
        return $this->belongsTo('App\Models\PackagingMaterial');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 31-mar-2022
        * uses : to get data of vendor  in recommendation engine
    */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 06-may-2022
        * uses : to get data of storage condition in recommendation engine
    */
    public function storage_condition()
    {
        return $this->belongsTo('App\Models\StorageCondition');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 11-may-2022
        * uses : to get data of storage condition in recommendation engine
    */
    public function measurement_unit()
    {
        return $this->belongsTo('App\Models\MeasurementUnit');
    }
}
