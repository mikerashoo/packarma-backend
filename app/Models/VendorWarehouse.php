<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorWarehouse extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 01-april-2022
     * uses : to get vendor details in vendor warehouse table
     */


    protected $fillable = [
        'warehouse_name',
        'vendor_id',
        'gstin',
        'mobile_no',
        'state_id',
        'country_id',
        'pincode',
        'flat',
        'area',
        'city_name',
    ];


    protected $hidden = [
        'status',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 01-april-2022
     * uses : to get city details in vendor warehouse table
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 01-april-2022
     * uses : to get state details in vendor warehouse table
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 01-april-2022
     * uses : to get country details in vendor warehouse table
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
