<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 29-mar-2022
        * uses : to get data of category model in sub category model 
    */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
