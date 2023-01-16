<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerContactUs extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Developed By :Maaz
     * Created On : 19/07/2022
     * Uses : The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'mobile',
        'subject',
        'details',
        'call_from',
        'ip_address',
    ];
    protected $hidden = [
        'call_from',
        'ip_address',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
