<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDevice extends Model
{
    use HasFactory;

    /**
     * Created by : Pradyumn Dwivedi
     * created on : 24/05/2022
     * uses : The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'imei_no',
        'fcm_id',
        'remember_token',
        'created_at',
        'updated_at',
    ];
}
