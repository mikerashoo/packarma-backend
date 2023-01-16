<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorageCondition extends Model
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
    public function setStorageConditionTitleAttribute($value)
    {
        $this->attributes['storage_condition_title'] = ucwords(strtolower($value));
    }

    public function setStorageConditionDescriptionAttribute($value)
    {
        $this->attributes['storage_condition_description'] = ucwords(strtolower($value));
    }
    // mutators end
}
