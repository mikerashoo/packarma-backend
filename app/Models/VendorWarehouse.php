<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorWarehouse extends Model
{
    use HasFactory;
    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 01-april-2022
     * uses : to get vendor details in vendor warehouse table
     */
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
