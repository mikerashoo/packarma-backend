<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserSubscriptionPayment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 03/06/2022
     * uses :  The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subscription_id',
        'transaction_id',
        'amount',
        'subscription_type',
        'payment_reference',
        'payment_unique_id',
        'payment_detais',
        'payment_status',
        'created_by'
    ];
    protected $appends = ['invoice_id', 'download_link'];


    public function getInvoiceIdAttribute()
    {
        $invoice = DB::table('invoices')->select('id')->where('subscription_id', $this->id)->first();
        return $invoice ? $invoice->id : null;

    }


    public function getDownloadLinkAttribute()
    {
        $invoice = UserInvoice::where('subscription_id', $this->id)->first();
        return $invoice ? $invoice->download_link : '';
    }

    /**
     * Developed By : Pradyumn Dwivedi
     * Created On : 24-mar-2022
     * uses : to to get data of user in user user subscription payment
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
}
