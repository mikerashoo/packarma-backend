<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

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
     * Created On : 30-mar-2022
     * uses : to to get data of sub category in product 
     */
    public function sub_category()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 30-mar-2022
     * uses : to to get data of category in product 
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 30-mar-2022
     * uses : to to get data of product form in product 
     */
    public function product_form()
    {
        return $this->belongsTo('App\Models\ProductForm');
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 30-mar-2022
     * uses : to to get data of product treatment in product 
     */
    public function packaging_treatment()
    {
        return $this->belongsTo('App\Models\PackagingTreatment');
    }
}
