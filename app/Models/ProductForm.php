<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];



    /**
     * Developed By : Maaz Ansari
     * Created On : 01-Aug-2022
     * uses : to change name to Camel Casing
     */

    // mutators start
    public function setProductFormNameAttribute($value)
    {
        $this->attributes['product_form_name'] = ucwords(strtolower($value));
    }

    public function setShortDescriptionAttribute($value)
    {
        $this->attributes['short_description'] = ucwords(strtolower($value));
    }
    // mutators end
}
