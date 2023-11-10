<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceAddress extends Model
{
    use HasFactory;

    /**
     * Created By : Pradyumn Dwivedi
     * Created On : 30/05/2022
     * uses : The attributes that are mass assignable.
     */

    protected $fillable = [
        'user_id',
        'name',
        'mobile_no',
        'billing_address',
        'country_id',
        'state_id',
        'city_name',

        'gstin',
        'pincode',
        'email',
    ];

    protected $appends = ['state_name', 'country_name'];


    public function getCountryNameAttribute()
    {
        $country = Country::select('country_name')->find($this->country_id);
        return $country ? $country->country_name : '';
    }


    public function getStateNameAttribute()
    {
        $state = State::select('state_name')->find($this->state_id);
        return $state ? $state->state_name : '';
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


}
