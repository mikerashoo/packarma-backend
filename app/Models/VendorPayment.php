<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    use HasFactory;
    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 06-April-2022
     * uses : to get vendor model data  in vendor payment table
     */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    
}
