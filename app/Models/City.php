<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

/**
    * Developed By : Pradyumn Dwivedi
    * Created On : 22-mar-2022
    * uses : to to get data of state model in city model 
    */
    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }
}
