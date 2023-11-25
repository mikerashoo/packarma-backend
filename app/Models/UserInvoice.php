<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use stdClass;

class UserInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'credit_id', 'subscription_id', 'amount'
    ];

    protected $appends = ['download_link', 'transaction_id', 'address', 'title',  'gstin', 'cid_number', 'pan_number', 'bank_name', 'branch_name', 'account_number', 'account_name', 'ifsc_code', 'gst_prices'];



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
        if ($this->subscription_id) {
            return DB::table('user_subscription_payments')->select('subscription_type')->where('id', $this->subscription_id)->first()->subscription_type;

        }

        return 'Buying credit';
    }

    public function getTransactionIdAttribute()
    {
        $subscriptionId = $this->subscription_id;
        if($subscriptionId){
            $transactionId = DB::table('user_subscription_payments')->select('transaction_id')->where('id', $subscriptionId)->first()->transaction_id;
            return $transactionId;
        }

        $creditId = $this->credit_id;
        if($creditId){
            $transactionId = DB::table('user_credit_histories')->select('transaction_id')->where('id', $creditId)->first()->transaction_id;
            return $transactionId;
        }
        return "";
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
        $cgst_total = $cgst == 0 || $total == 0 ? 0 : $this->calculatePercentage($cgst, $total);
        $sgst_total =  $sgst == 0 || $total == 0 ? 0 :  $this->calculatePercentage($sgst, $total);
        $igst_total =  $igst == 0 || $total == 0 ? 0 : $this->calculatePercentage($igst, $total);


        $data = new stdClass;
        $data->igst = $igst;
        $data->cgst = $cgst;
        $data->sgst = $sgst;

        $data->igst_total = $igst_total;
        $data->cgst_total = $cgst_total;
        $data->sgst_total = $sgst_total;


        $data->total = $total + $igst_total + $cgst_total + $sgst_total;
        $data->sub_total = $total;


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

    private function calculatePercentage($percentage, $total)
    {
        if ($total == 0) {
            return 0;
        }

        $value = ($percentage * $total) / 100;
        return round($value, 2);
    }


    /**
     * Get the subscription that owns the UserInvoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscriptionPayment::class, 'subscription_id');
    }


    /**
     * Get the subscription that owns the UserInvoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function credit(): BelongsTo
    {
        return $this->belongsTo(UserCreditHistory::class, 'credit_id');
    }


    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfSubscription($query, $subscriptionId)
    {
        return $query->where('subscription_id', $subscriptionId);
    }

    public function getDownloadLinkAttribute()
    {
        $invoiceId = $this->id;


        $invoiceDir = "app/attachments";
        $storagePath =  storage_path($invoiceDir);

        $prefix = $this->subscription_id ? 'subscription_' : 'credit_';
        $filename = $prefix . 'invoice_' . $invoiceId . '.pdf';
        $filePath = $invoiceDir . '/' . $filename;

        $exists = \Storage::disk('s3')->has($filePath);

        if ($exists) {
            return Storage::disk('s3')->temporaryUrl($filePath, now()->addMinutes(30));
        }

        $invoice = $this;

        $invoice->addres;
        $invoice->user;

        $invoice->gst_prices;
        $financialYear = (date('m') > 4) ?  date('Y') . '-' . substr((date('Y') + 1), -2) : (date('Y') - 1) . '-' . substr(date('Y'), -2);
        $invoiceDate = Carbon::now()->format('d/m/Y');
        $orderDate = Carbon::parse($this->created_at)->format('d/m/Y');
        $inWords = currencyConvertToWord($this->gst_prices->total);


        $logo = public_path() . "/backend/img/Packarma_logo.png";
        $orderFormatedId = getFormatid($invoiceId, 'orders');


        $result = [
            'invoice' => $this,
            'invoiceDate' => $invoiceDate,
            'orderDate' => $orderDate,
            'no_image' => $logo,
            'financialYear' => $financialYear,
            'in_words' => $inWords,
            'orderFormatedId' => $orderFormatedId,
            'transactionId' => $invoice->transaction_id,
        ];





        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $html =  view('invoice.invoice_pdf', $result);
        $pdf->SetTitle('Order Invoice');
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $file = $pdf->output($filename, 'S');
        Storage::disk('s3')->put($filePath, $file);

        return Storage::disk('s3')->temporaryUrl($filePath, now()->addMinutes(30));
    }
}
