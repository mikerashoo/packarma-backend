<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerEnquiry extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'user_id',
        'category_id',
        'sub_category_id',
        'product_id',
        'shelf_life',
        'entered_shelf_life',
        'entered_shelf_life_unit',
        'product_weight',
        'measurement_unit_id',
        'product_quantity',
        'storage_condition_id',
        'packaging_machine_id',
        'product_form_id',
        'packing_type_id',
        'packaging_treatment_id',
        'recommendation_engine_id',
        'packaging_material_id',
        'user_address_id',
        'country_id',
        'state_id',
        'city_name',
        'flat',
        'area',
        'land_mark',
        'pincode',
        'created_by'
    ];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of user in customer enquiry table
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of vendor in customer enquiry table
     */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor')->withTrashed();
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of city in customer enquiry table
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of state in customer enquiry table
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of sub country in customer enquiry table
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of product in customer enquiry table
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of category in customer enquiry table
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of sub category in customer enquiry table
     */
    public function sub_category()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of packing type in customer enquiry table
     */
    public function product_form()
    {
        return $this->belongsTo('App\Models\ProductForm');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of packing type in customer enquiry table
     */
    public function packing_type()
    {
        return $this->belongsTo('App\Models\PackingType');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of vendor quotation in customer enquiry table
     */
    public function vendor_quotation()
    {
        return $this->belongsTo('App\Models\VendorQuotation');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of packaging machine in customer enquiry table
     */
    public function packaging_machine()
    {
        return $this->belongsTo('App\Models\PackagingMachine');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of packaging material in customer enquiry table
     */
    public function packaging_material()
    {
        return $this->belongsTo('App\Models\PackagingMaterial');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of storage condition  in customer enquiry table
     */
    public function storage_condition()
    {
        return $this->belongsTo('App\Models\StorageCondition');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of packaging treatment in customer enquiry table
     */
    public function packaging_treatment()
    {
        return $this->belongsTo('App\Models\PackagingTreatment');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of vendor warehouse in customer enquiry table
     */
    public function vendor_warehouse()
    {
        return $this->belongsTo('App\Models\VendorWarehouse')->withTrashed();
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 11-may-2022
     * uses : to get data of measurement unit in customer enquiry table
     */
    public function measurement_unit()
    {
        return $this->belongsTo('App\Models\MeasurementUnit');
    }


    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 11-may-2022
     * uses : to get data of user address in customer enquiry table
     */
    public function user_address()
    {
        return $this->belongsTo('App\Models\UserAddress')->withTrashed();
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 17-may-2022
     * uses : to get data of recommendation engine data customer enquiry table
     */
    public function recommendation_engine()
    {
        return $this->belongsTo('App\Models\RecommendationEngine');
    }

    /**
     *
     * uses : to get data of recommendation engines data customer enquiry table
     */
    public function recommendationEngines()
    {
        return $this->belongsToMany('App\Models\RecommendationEngine', 'customer_enquiries_recommendations', 'customer_enquiry_id', 'recommendation_id');
    }
}
