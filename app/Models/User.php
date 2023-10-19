<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
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
        'name',
        'email',
        'phone_country_id',
        'phone',
        'whatsapp_no',
        'password',
        'visiting_card_front',
        'visiting_card_back',
        'subscription_id',
        'subscription_start',
        'subscription_end',
        'type',
        'gstin',
        'gst_certificate',
        'current_credit_amount',
        'credit_totals'
    ];

    public function getCreditTotalsAttribute($credit_totals)
    {

        $current = $this->current_credit_amount;

        return $credit_totals >= $current ? $credit_totals : $current;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'fpwd_flag',
        'is_verified'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get phone code for phone number in user table
     */
    public function phone_country()
    {
        return $this->belongsTo('App\Models\Country', 'phone_country_id', 'id');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get phone code for whatsapp number in user table
     */
    public function whatsapp_country()
    {
        return $this->belongsTo('App\Models\Country', 'whatsapp_country_id', 'id');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to to get data of state in user table
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }
    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to to get data of city in user table
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to to get data of user address in user table
     */
    public function userAddress()
    {
        return $this->belongsTo('App\Models\UserAddress');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to to get data of country in user table
     */
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    // mutators start
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = trim(strtolower($value));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }
    // mutators end
}
