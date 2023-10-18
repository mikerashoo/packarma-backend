<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCreditHistory extends Model
{
    use HasFactory;
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'amount',
        'user_id',
        'action',
        'reason',
        'transaction_id',
        'amount_paid',
        'enquery_id',
        'expire_date',
        'created_at'
    ];
}
