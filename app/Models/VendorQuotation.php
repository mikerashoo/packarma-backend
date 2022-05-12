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
        'vendor.*',
        // 'warehouse.*',
        'vendor_price.*',
        'commission_rate.*',
        'quotation_validity.*',
        'etd.*',
    ];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 13-april-2022
     * uses : to get user data in vendor quotation table
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 13-april-2022
     * uses : to get vendor data in vendor quotation table
     */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
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
    public function Enquiry()
    {
        return $this->belongsTo('App\Models\CustomerEnquiry');
    }
}
