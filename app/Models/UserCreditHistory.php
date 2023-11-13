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
            return null;
        }

        $invoiceId = $invoice->id;

        $invoiceDir = "app/attachments";
        $storagePath =  storage_path($invoiceDir);
        $filename = 'invoice_' . $invoiceId . '.pdf';
        $filePath = $invoiceDir . '/' . $filename;

        $exists = Storage::disk('s3')->has($filePath);
        if ($exists) {
            return Storage::disk('s3')->temporaryUrl($filePath, now()->addMinutes(30));
        }

        $invoice->address;
        $invoice->user;
        $financialYear = (date('m') > 4) ?  date('Y') . '-' . substr((date('Y') + 1), -2) : (date('Y') - 1) . '-' . substr(date('Y'), -2);
        $invoiceDate = Carbon::now()->format('d/m/Y');
        $orderDate = Carbon::parse($invoice->created_at)->format('d/m/Y');
        $inWords = currencyConvertToWord($invoice->gst_prices->total);
        $transactionId = $this->transaction_id;

        $logo = public_path() . "/backend/img/Packarma_logo.png";
        $orderFormatedId = getFormatid($invoiceId, 'orders');
        $result = [
            'invoice' => $invoice,
            'invoiceDate' => $invoiceDate,
            'orderDate' => $orderDate,
            'no_image' => $logo,
            'financialYear' => $financialYear,
            'in_words' => $inWords,
            'orderFormatedId' => $orderFormatedId,
            'transactionId' => $transactionId,
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
