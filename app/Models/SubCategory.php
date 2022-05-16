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
     * Created On : 06/05/2022
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
        * Developed By : Pradyumn Dwivedi
        * Created On : 29-mar-2022
        * uses : to get data of category model in sub category model 
    */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
