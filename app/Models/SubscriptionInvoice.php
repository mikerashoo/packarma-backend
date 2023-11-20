<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use stdClass;

class SubscriptionInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_subscription_id',
    ];

    protected $appends = ['address', 'gstin', 'cid_number', 'pan_number', 'bank_name', 'branch_name', 'account_number', 'account_name', 'ifsc_code', 'gst_prices'];


    public function getGstInAttribute()
    {
        return config("bankDetails.GSTIN");
    }


    public function getCidNumberAttribute()
    {
        return config("bankDetails.CID_NUMNER");
    }

    public function getPanNumberAttribute()
    {
        return config("bankDetails.PAN_NUMNER");
    }

    public function getBankNameAttribute()
    {
        return config("bankDetails.BANK_NAME");
    }


    public function getBranchNameAttribute()
    {
        return config("bankDetails.BRANCH_NAME");
    }


    public function getAccountNumberAttribute()
    {
        return config("bankDetails.ACCOUNT_NUMBER");
    }


    public function getAccountNameAttribute()
    {
        return config("bankDetails.ACCOUNT_NAME");
    }

    public function getIfscCodeAttribute()
    {
        return config("bankDetails.BRANCH_NAME");
    }


    public function getGstPricesAttribute()
    {


        $stateName = $this->address->state_name;

        $total = UserSubscriptionPayment::select('amount')->find($this->user_subscription_id)->amount;


        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        $igst_total = 0;
        $cgst_total = 0;
        $sgst_total = 0;
        $maharashtra = 'maharashtra';
        if ($maharashtra == strtolower($stateName)) {
            $cgst = 9;
            $sgst = 9;
        } else {
            $igst = 18;
        }
        $cgst_total =  round(($total * $cgst) / 100, 2);
        $sgst_total =  round(($total * $sgst) / 100, 2);
        $igst_total =  round(($total * $igst) / 100, 2);


        $data = new stdClass;
        $data->igst = $igst;
        $data->cgst = $cgst;
        $data->sgst = $sgst;

        $data->igst_total = $igst_total;
        $data->cgst_total = $cgst_total;
        $data->sgst_total = $sgst_total;


        $data->sub_total = $total - $igst_total - $cgst_total - $sgst;
        $data->total = $total;


        return $data;
    }



    /**
     * Get the user that owns the SubscriptionInvoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }




    /**
     * Get the subscription that owns the SubscriptionInvoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscriptionPayment::class, 'user_subscription_id');
    }

    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfSubscription($query, $subscriptionId)
    {
        return $query->where('user_subscription_id', $subscriptionId);
    }
}
