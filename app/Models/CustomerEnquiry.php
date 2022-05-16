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
        'order_id',
        'category',
        'sub_category',
        'product',
        'shelf_life',
        'product_weight',
        'measurement_unit_id',
        'storage_condition',
        'packaging_machine',
        'product_form',
        'packing_type',
        'packaging_treatment',
        // 'quantity',
        // 'quote_type',
        // 'country,
        // 'state',
        // 'city',
        'pincode',
        // 'address'
    ];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 11/05/2022
     * Uses : The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'status',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of user in customer enquiry table
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 04-mar-2022
     * uses : to get data of vendor in customer enquiry table
     */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
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
        return $this->belongsTo('App\Models\VendorWarehouse');
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
}
