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

    protected $appends = ['invoice_id'];


    public function getInvoiceIdAttribute()
    {
        $invoice = UserInvoice::select('id AS invoice_id')->where('credit_id', $this->id)->first();
        return $invoice ? $invoice->invoice_id : null;
    }
}
