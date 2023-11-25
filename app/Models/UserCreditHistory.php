<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
        'add',
        'deduct',
        'expire_date',
        'created_at'
    ];

    protected $appends = ['invoice_id', 'download_link'];


    public function getInvoiceIdAttribute()
    {
        $invoice = UserInvoice::select('id AS invoice_id')->where('credit_id', $this->id)->first();
        return $invoice ? $invoice->invoice_id : null;
    }

    public function getDownloadLinkAttribute()
    {
        $invoice = UserInvoice::where('credit_id', $this->id)->first();
        if (!$invoice) {
            return '';
        }

       return $invoice->download_link;
    }
}
