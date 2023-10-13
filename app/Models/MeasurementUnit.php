<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeasurementUnit extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unit_name',
        'unit_symbol'
    ];



    /**
     * Developed By : Maaz Ansari
     * Created On : 01-Aug-2022
     * uses : to change name to Camel Casing
     */

    // mutators start
    public function setUnitNameAttribute($value)
    {
        $this->attributes['unit_name'] = ucwords(strtolower($value));
    }

    public function setUnitSymbolAttribute($value)
    {
        $this->attributes['unit_symbol'] = ucwords(strtolower($value));
    }
    // mutators end

}
