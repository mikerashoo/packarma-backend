<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'whatsapp_no',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        return $this->belongsTo('App\Models\Country','phone_country_id','id');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 22-mar-2022
     * uses : to get phone code for whatsapp number in user table
     */
    public function whatsapp_country()
    {
        return $this->belongsTo('App\Models\Country','whatsapp_country_id','id');
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
}
