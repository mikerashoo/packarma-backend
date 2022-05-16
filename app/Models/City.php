<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 13/05/2022
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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'state',
        'city_name'
    ];

    /**
    * Developed By : Pradyumn Dwivedi
    * Created On : 22-mar-2022
    * uses : to to get data of state model in city model 
    */
    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

    /**
    * Developed By : Pradyumn Dwivedi
    * Created On : 22-mar-2022
    * uses : to to get data of country model in city model 
    */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
