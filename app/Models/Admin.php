<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
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

}
