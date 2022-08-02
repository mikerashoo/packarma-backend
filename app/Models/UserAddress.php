<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Created By : Pradyumn Dwivedi
     * Created On : 30/05/2022
     * uses : The attributes that are mass assignable.
     */

    protected $fillable = [
        'user_id',
        'country_id',
        'address_name',
        'type',
        'gstin',
        'mobile_no',
        'pincode',
        'flat',
        'area',
        'land_mark',
        'city_name',
        'state_id',
        'status'
    ];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 24-mar-2022
     * uses : to to get data of city in user address table
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 24-mar-2022
     * uses : to to get data of state in user address table
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 24-mar-2022
     * uses : to to get data of country in user address table
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 24-mar-2022
     * uses : to to get data of user in user address table
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * Developed By : Maaz
     * Created On : 01-Aug-2022
     */

    // mutators start

    public function setAddressNameAttribute($value)
    {
        $this->attributes['address_name'] = ucwords(strtolower($value));
    }

    public function setCityNameAttribute($value)
    {
        $this->attributes['city_name'] = ucwords(strtolower($value));
    }

    public function setFlatAttribute($value)
    {
        $this->attributes['flat'] = ucwords(strtolower($value));
    }
    public function setAreaAttribute($value)
    {
        $this->attributes['area'] = ucwords(strtolower($value));
    }

    public function setLandMarkAttribute($value)
    {
        $this->attributes['land_mark'] = ucwords(strtolower($value));
    }
    // mutators end
}
