<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use stdClass;

class UserInvoice extends Model
{
    use HasFactory;

    protected $GSTIN = "27AAMCP2500K1ZD";
    protected $CID_NUMNER = "U74999MH2021PTC366605";
    protected $PAN_NUMNER = "AAMCP2500K";
    protected $BANK_NAME = "HDFC bank";
    protected $BRANCH_NAME = "Vile Parle ";
    protected $ACCOUNT_NUMBER = "50200064942088";
    protected $IFSC_CODE = "HDFC0000227";
    protected $ACCOUNT_NAME = "Packarma";


    protected $appends = ['attachment', 'address', 'title',  'gstin', 'cid_number', 'pan_number', 'bank_name', 'branch_name', 'account_number', 'account_name', 'ifsc_code', 'gst_prices'];


    public function getGstInAttribute()
    {
        return $this->GSTIN;
    }


    public function getAttachmentAttribute()
    {
        // Generate a dynamic file name (e.g., based on timestamp)
        $fileName = 'invoice_' . $this->id . '.pdf';

        // Define the directory path for saving the PDF in the storage directory
        $storagePath = storage_path('app/public/invoices/');
        $localFilePath = $storagePath . $fileName;


        if (Storage::exists($localFilePath)) {
            // File exists
            return  $url = Storage::url($localFilePath);
        }
        // Check if the directory exists, if not, create it
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }


        $this->address->state;
        $this->user;
        // return $this;
        $this->subscription;
        $this->this;
        $transactionId = "";
        if ($this->subscription) {
            $transactionId = $this->subscription->transaction_id;
        } else if ($this->credit) {
            $transactionId = $this->credit->transaction_id;
        }

        $financialYear = (date('m') > 4) ?  date('Y') . '-' . substr((date('Y') + 1), -2) : (date('Y') - 1) . '-' . substr(date('Y'), -2);
        $invoiceDate = Carbon::now()->format('d/m/Y');
        $orderDate = Carbon::parse($this->created_at)->format('d/m/Y');
        $inWords = currencyConvertToWord($this->gst_prices->total);


        $logo = public_path() . "/backend/img/Packarma_logo.png";
        $orderFormatedId = getFormatid($this->id, 'orders');

        $result = [
            'invoice' => $this,
            'invoiceDate' => $invoiceDate,
            'orderDate' => $orderDate,
            'no_image' => $logo,
            'financialYear' => $financialYear,
            'in_words' => $inWords,
            'orderFormatedId' => $orderFormatedId,
            'transactionId' => $transactionId
        ];

        // Create a TCPDF instance
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set TCPDF options as needed
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Add a page
        $pdf->AddPage();
        // Load your view into the TCPDF instance
        $view = view('invoice.invoice_pdf', $result)->render();
        $pdf->writeHTML($view, true, false, true, false, '');

        // Convert PDF to binary data
        $pdfData = $pdf->output('', 'S');

        // Save the generated PDF to the storage directory
        Storage::put($storagePath . $fileName, $pdfData);

        // Get the URL of the stored PDF
        $publicUrl = Storage::url($storagePath . $fileName);
        // Optionally, you can return the URL or any other response as needed
        $publicUrl = str_replace('\\', '/', $publicUrl);
        return  $url = Storage::url($localFilePath);

        return  asset($publicUrl);

        // Optionally, you can return the S3 file path or any other response as needed
        // return response()->json(['message' => 'PDF generated and uploaded to S3 s
        // // Load your view into the TCPDF instance
        // $view = view('invoice.invoice_pdf', $result)->render();
        // $pdf->writeHTML($view, true, false, true, false, '');

        // // Store the generated PDF to the public directory
        // $pdf->Output($directory . $fileName, 'F');

        // After saving the PDF, upload it to S3 using your existing saveSingleImage function
        // $s3FilePath = saveSingleImage($file, 's3-path', $fileName);

        // return $fileName;
    }

    public function saveSingleImage($file, $type = "", $id = "", $extension = "")
    {
        $actualImagePath = $type;

        // If the extension is not provided, attempt to extract it from the file name
        if (empty($extension)) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
        }

        $originalImageName = $type . '_' . $id . '.' . $extension;

        Storage::disk("s3")->putFileAs($actualImagePath, $file, $originalImageName);

        return $originalImageName;
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
        return $this->belongsTo(UserSubscriptionPayment::class, 'user_subscription_id');
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
        return $query->where('user_subscription_id', $subscriptionId);
    }
}
