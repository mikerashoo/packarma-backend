<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackagingMaterial extends Model
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
    public function setPackagingMaterialNameAttribute($value)
    {
        $this->attributes['packaging_material_name'] = strtoupper($value);
    }

    public function setMaterialDescriptionAttribute($value)
    {
        $this->attributes['material_description'] = ucwords(strtolower($value));
    }
    // mutators end
}
