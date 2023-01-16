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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sub_category_name',
        'category_id',
        'sub_category_image'
    ];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 29-mar-2022
     * uses : to get data of category model in sub category model 
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * Developed By : Maaz Ansari
     * Created On : 01-Aug-2022
     * uses : to change name to Camel Casing
     */

    // mutators start
    public function setSubCategoryNameAttribute($value)
    {
        $this->attributes['sub_category_name'] = ucwords(strtolower($value));
    }
    // mutators end
}
