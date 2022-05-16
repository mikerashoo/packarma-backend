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
     * Developed By : Pradyumn Dwivedi
     * Created On : 12/05/2022
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
}
