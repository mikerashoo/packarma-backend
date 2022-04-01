<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get phone code for phone number in vendor table
     */
    public function phone_country()
    {
        return $this->belongsTo('App\Models\Country','phone_country_id','id');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get phone code for whatsapp number in vendor table
     */
    public function whatsapp_country()
    {
        return $this->belongsTo('App\Models\Country','whatsapp_country_id','id');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get phone currency in vendor table
     */
    public function currency()
    {
        return $this->belongsTo('App\Models\Country');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get vendor material mapping in vendor table
     */
    public function vendor_material_mapping()
    {
        return $this->belongsTo('App\Models\VendorMaterialMapping');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get packaging material in vendor table
     */
    public function packaging_material()
    {
        return $this->belongsTo('App\Models\PackagingMaterial');
    }
}
