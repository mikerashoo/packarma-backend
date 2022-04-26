<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
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
