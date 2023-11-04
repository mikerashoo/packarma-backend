<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use stdClass;

class SubscriptionInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_subscription_id',
    ];
    protected $GSTIN = "18AABCU9603R1ZK";
    protected $CID_NUMNER = "U74999MH2021PTC366605";
    protected $PAN_NUMNER = "AAMCP2500K";
    protected $BANK_NAME = "HDFC bank";
    protected $BRANCH_NAME = "Vile Parle ";
    protected $ACCOUNT_NUMBER = "50200064942088";
    protected $IFSC_CODE = "HDFC0000227";


    protected $appends = ['address', 'gstin', 'cid_number', 'pan_number', 'bank_name', 'branch_name', 'account_number', 'ifsc_code', 'gst_prices'];


    public function getGstInAttribute()
    {
        return $this->GSTIN;
    }

    public function getCidNumberAttribute()
    {
        return $this->CID_NUMNER;
    }

    public function getPanNumberAttribute()
    {
        return $this->PAN_NUMNER;
    }

    public function getBankNameAttribute()
    {
        return $this->BANK_NAME;
    }


    public function getBranchNameAttribute()
    {
        return $this->BRANCH_NAME;
    }


    public function getAccountNumberAttribute()
    {
        return $this->ACCOUNT_NUMBER;
    }

    public function getIfscCodeAttribute()
    {
        return $this->BRANCH_NAME;
    }

    public function getAddressAttribute()
    {
        return InvoiceAddress::where('user_id', $this->user_id)->first();
    }


    public function getGstPricesAttribute()
    {


        $stateName = $this->address->state_name;

        $total = UserSubscriptionPayment::select('amount')->find($this->user_subscription_id)->amount;


        $igst = 0;
        $cgst = 0;
        $sgst = 0;
        $maharashtra = 'maharashtra';
        if ($maharashtra == strtolower($stateName)) {
            $cgst =  round(($total * 9) / 100, 2);
            $sgst =  round(($total * 9) / 100, 2);
        } else {
            $igst =  round(($total * 9) / 100, 2);
        }

        $data = new stdClass;
        $data->igst = $igst;
        $data->cgst = $cgst;
        $data->sgst = $sgst;
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
