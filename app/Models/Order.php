<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of user  in order table
    */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of vendor  in order table
    */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of product  in order table
    */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of category  in order table
    */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of sub category  in order table
    */
    public function sub_category()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of storage condition  in order table
    */   
    public function storage_condition()
    {
        return $this->belongsTo('App\Models\StorageCondition');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of packaging machine  in order table
    */
    public function packaging_machine()
    {
        return $this->belongsTo('App\Models\PackagingMachine');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of product form  in order table
    */
    public function product_form()
    {
        return $this->belongsTo('App\Models\ProductForm');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of packing type  in order table
    */
    public function packing_type()
    {
        return $this->belongsTo('App\Models\PackingType');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of packaging treatment  in order table
    */
    public function packaging_treatment()
    {
        return $this->belongsTo('App\Models\PackagingTreatment');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 07-may-2022
        * uses : to get data of packaging material in order table
    */
    public function packaging_material()
    {
        return $this->belongsTo('App\Models\PackagingMaterial');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of country  in order table
    */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of currency  in order table
    */
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 11-may-2022
        * uses : to get data of measurement unit in order table
    */
    public function measurement_unit()
    {
        return $this->belongsTo('App\Models\MeasurementUnit');
    }
}
