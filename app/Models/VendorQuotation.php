<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorQuotation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enquiry_status',
        'product_quantity',
        'sub_total',
        'total_amount',
        'user_id',
        'customer_enquiry_id',
        'product_id',
        'vendor_id',
        'vendor_price',
        'commission_amt',
        'gst_type',
        'gst_percentage',
        'mrp',
        'gst_amount',
        'freight_amount',
        'delivery_charges',
        'total_amount',
        'quotation_expiry_datetime',
        'vendor_warehouse_id',
        'lead_time',
        'created_by',
    ];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 13-april-2022
     * uses : to get user data in vendor quotation table
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 13-april-2022
     * uses : to get vendor data in vendor quotation table
     */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor')->withTrashed();
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 13-april-2022
     * uses : to get product data in vendor quotation table
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 13-april-2022
     * uses : to get warehouse data in vendor quotation table
     */
    public function vendor_warehouse()
    {
        return $this->belongsTo('App\Models\VendorWarehouse');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 13-april-2022
     * uses : to get warehouse data in vendor quotation table
     */
    public function enquiry()
    {
        return $this->belongsTo('App\Models\CustomerEnquiry');
    }
}
