<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
/**
    * Developed By : Pradyumn Dwivedi
    * Created On : 22-mar-2022
    * uses : to to get data of state model in city model 
    */
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

}
