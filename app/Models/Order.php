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
     * Created On : 30-05-2022
     * uses : The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'vendor_quotation_id',
        'vendor_id',
        'vendor_warehouse_id',
        'customer_enquiry_id',
        'user_address_id',
        'category_id',
        'sub_category_id',
        'product_id',
        'shelf_life',
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
        'currency_id',
        'mrp',
        'sub_total',
        'gst_amount',
        'gst_type',
        'gst_percentage',
        'freight_amount',
        'delivery_in_days',
        'grand_total',
        'commission',
        'vendor_amount',
        'customer_pending_payment',
        'customer_payment_status',
        'vendor_pending_payment',
        'vendor_payment_status',
        'order_delivery_status',
        'order_details',
        'product_details',
        'shipping_details',
        'billing_details',
        'created_by'
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at',
        'commission',
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of vendor  in order table
     */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor')->withTrashed();
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

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 14-june-2022
     * uses : to get data of packaging solution in order table
     */
    public function recommendation_engine()
    {
        return $this->belongsTo('App\Models\RecommendationEngine');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 14-june-2022
     * uses : to get data of user address in order table
     */
    public function user_address()
    {
        return $this->belongsTo('App\Models\UserAddress');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 14-june-2022
     * uses : to get data of state in order table
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 12-july-2022
     * uses : To check order id in review table
     */
    public function review()
    {
        return $this->belongsTo('App\Models\Review');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 12-july-2022
     * uses : To get vendor quotation data 
     */
    public function vendor_quotation()
    {
        return $this->belongsTo('App\Models\Review');
    }
}
