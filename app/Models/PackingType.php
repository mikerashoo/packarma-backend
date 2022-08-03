<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackingType extends Model
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
    public function setPackingNameAttribute($value)
    {
        $this->attributes['packing_name'] = ucwords(strtolower($value));
    }

    public function setPackingDescriptionAttribute($value)
    {
        $this->attributes['packing_description'] = ucwords(strtolower($value));
    }
    // mutators end



}
