<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorPayment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 06-April-2022
     * uses : to get vendor model data  in vendor payment table
     */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }


    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 06-April-2022
     * uses : to get vendor model data  in vendor payment table
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
