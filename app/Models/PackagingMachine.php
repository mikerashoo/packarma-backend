<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackagingMachine extends Model
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
    public function setPackagingMachineNameAttribute($value)
    {
        $this->attributes['packaging_machine_name'] = ucwords(strtolower($value));
    }

    public function setPackagingMachineDescriptionAttribute($value)
    {
        $this->attributes['packaging_machine_description'] = ucwords(strtolower($value));
    }
    // mutators end
}
