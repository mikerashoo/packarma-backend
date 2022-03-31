<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
/**
    * Developed By : Pradyumn Dwivedi
    * Created On : 22-mar-2022
    * uses : to to get data of country model in state model 
*/
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
