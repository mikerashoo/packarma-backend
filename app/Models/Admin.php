<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 05-april-2022
     * uses : to to get data of role model in staff table 
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 05-april-2022
     * uses : to to get data of country model in staff table 
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }


    /**
     * Developed By : Maaz Ansari
     * Created On : 01-Aug-2022
     * uses : to change name to Camel Casing
     */

    // mutators start
    public function setAdminNameAttribute($value)
    {
        $this->attributes['admin_name'] = ucwords(strtolower($value));
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = ucwords(strtolower($value));
    }

    // mutators end
}
