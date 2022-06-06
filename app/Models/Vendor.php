<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model implements JWTSubject
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vendor_name',
        'vendor_company_name',
        'phone_country_id',
        'phone',
        'vendor_email',
        'vendor_password',
        'gstin',
        'gst_certificate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */



    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get phone code for phone number in vendor table
     */
    public function phone_country()
    {
        return $this->belongsTo('App\Models\Country', 'phone_country_id', 'id');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get phone code for whatsapp number in vendor table
     */
    public function whatsapp_country()
    {
        return $this->belongsTo('App\Models\Country', 'whatsapp_country_id', 'id');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get phone currency in vendor table
     */
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
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
