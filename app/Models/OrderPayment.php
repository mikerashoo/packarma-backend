<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;
    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of user  in order payment table
    */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of vendor in order payment table
    */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 04-mar-2022
        * uses : to get data of product  in order payment table
    */
    public function product()
    {
        return $this->belongsTo('App\Models\product');
    }
}
