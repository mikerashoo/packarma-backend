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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'vendor_password',
        'fpwd_flag',
    ];



    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get phone code for phone number in vendor table
     */
    public function phone_country()
    {
        return $this->belongsTo('App\Models\Country', 'phone_country_id', 'id')->withTrashed();
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get phone code for whatsapp number in vendor table
     */
    public function whatsapp_country()
    {
        return $this->belongsTo('App\Models\Country', 'whatsapp_country_id', 'id')->withTrashed();
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
        return $this->belongsTo('App\Models\PackagingMaterial')->withTrashed();
    }

    // mutators start
    public function setVendorEmailAttribute($value)
    {
        $this->attributes['vendor_email'] = trim(strtolower($value));
    }

    public function setGstinAttribute($value)
    {
        $this->attributes['gstin'] = strtoupper($value);
    }

    public function setVendorNameAttribute($value)
    {
        $this->attributes['vendor_name'] = ucwords(strtolower($value));
    }

    public function setVendorCompanyNameAttribute($value)
    {
        $this->attributes['vendor_company_name'] = ucwords(strtolower($value));
    }
    // mutators end
}
