<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDevice extends Model
{
    use HasFactory;
    /**
     * Developed By : Maaz
     * Created On : 20-may-2022
     * uses : to get vendor device
     */

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
