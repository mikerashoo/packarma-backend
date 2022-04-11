<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 11-april-2022
        * uses : to get data of user in review 
    */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

   /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 11-april-2022
        * uses : to get data of product in review 
    */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
