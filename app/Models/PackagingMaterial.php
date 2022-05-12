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
     * Created On : 11 may 2022
     * Uses : The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'status',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Developed By : Maaz Ansari
     * Created On : 11 may 2022
     * uses : to to get data of sub packing type in packaging material 
     */
    public function packing_type()
    {
        return $this->belongsTo('App\Models\PackingType');
    }

    /**
     * Developed By : Maaz Ansari
     * Created On : 11 may 2022
     * uses : to to get data of product in packaging material 
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
