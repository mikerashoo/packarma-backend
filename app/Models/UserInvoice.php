<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;
use stdClass;

class UserInvoice extends Model
{
    use HasFactory;

    protected $GSTIN = "18AABCU9603R1ZK";
    protected $CID_NUMNER = "U74999MH2021PTC366605";
    protected $PAN_NUMNER = "AAMCP2500K";
    protected $BANK_NAME = "HDFC bank";
    protected $BRANCH_NAME = "Vile Parle ";
    protected $ACCOUNT_NUMBER = "50200064942088";
    protected $IFSC_CODE = "HDFC0000227";
    protected $ACCOUNT_NAME = "Packarma";


    protected $appends = ['address', 'title',  'gstin', 'cid_number', 'pan_number', 'bank_name', 'branch_name', 'account_number', 'account_name', 'ifsc_code', 'gst_prices'];


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


    public function getAccountNameAttribute()
    {
        return $this->ACCOUNT_NAME;
    }

    public function getIfscCodeAttribute()
    {
        return $this->BRANCH_NAME;
    }

    public function getAddressAttribute()
    {
        $invoiceAddressState = InvoiceAddress::where('user_id', $this->user_id)->first();
        if ($invoiceAddressState) {
            return $invoiceAddressState;
        }

        return UserAddress::where('user_id', $this->user_id)->first();
    }


    public function getTitleAttribute()
    {
        if ($this->subscription) {
            return $this->subscription->type + ' subscription';
        }

        return 'Buying credit';
    }



    public function getGstPricesAttribute()
    {


        $stateName = $this->address ? State::where('id', $this->address->state_id)->select('state_name')->first()->state_name : '';


        $total = $this->amount;


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
     * Get the user that owns the UserInvoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }




    /**
     * Get the subscription that owns the UserInvoice
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