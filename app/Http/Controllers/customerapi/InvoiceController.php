<?php

/**
 * Created By :Mikiyas Birhanu
 * Created On : 02 Nov 2024
 * Uses : This controller will be used to invoice .
 */

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\InvoiceAddress;
use App\Models\SubscriptionInvoice;
use App\Models\UserInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

// - CIN
// - PAN

// - INVOICE NO
// - INVOICE DATE
// - STATE
// - STATE CODE

// - CUSTOMER NAME
// - ADDRESS
// - GSTIN/UIN: {{$billing_data->gstin ??''}}
// -  State: {{$billing_data->state_name ??''}}     Code: {{$billing_data->country_name ??''}} - {{$billing_data->state_name ??''}}<

class InvoiceController extends Controller
{

    // GST Number:-27AAMCP2500K1ZD

    /**
     *   created by : Mikiyas Birhanu
     *   @param Request request
     *   @return Response
     */
    public function index($invoiceId)
    {
        try {
            $invoice = SubscriptionInvoice::find($invoiceId);
            Log::info("Invoice data fetch successfully");
            successMessage(__('customer_enquiry.customer_enquiry_placed_successfully'), $invoice);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unkown error occured',
                'error' => $e->getMessage()
            ], 500);
            Log::error("Invoice data fetch creation failed: " . $e->getMessage());
        }
    }

    /**
     *   created by : Mikiyas Birhanu
     *   @param Request request
     *   @return Response
     */
    public function store(Request $request)
    {
        try {

            $validateRequest = Validator::make(
                $request->all(),
                [
                    'user_id' => ['required', Rule::exists('users', 'id')],
                    'user_subscription_id' => ['required', Rule::exists('user_subscription_payments', 'id')->where('user_id', $request->user_id)],
                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }

            $userId =  $request->user_id;
            $userSubscriptionPaymentId = $request->user_subscription_id;
            $addressExists = DB::table('invoice_addresses')->select('id')->where('user_id', $request->user_id)->first();

            if (!$addressExists) {

                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => 'User has no invoice address'
                ], 401);
            }

            $exists = SubscriptionInvoice::ofUser($userId)->ofSubscription($userSubscriptionPaymentId)->count() > 0;
            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => 'Invoice With The Same User And Subscription Exists'
                ], 401);
            }

            $invoice = SubscriptionInvoice::create([
                'user_id' => $userId,
                'user_subscription_id' => $userSubscriptionPaymentId,
            ]);

            Log::info("Invoice data fetch successfully");
            successMessage(__('invoice.save_success'), $invoice->toArray());
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unkown error occured',
                'error' => $e->getMessage()
            ], 500);
            Log::error("Invoice data fetch creation failed: " . $e->getMessage());
        }
    }

    /**
     *   created by : Mikiyas Birhanu
     *   @param Request request
     *   @return Response
     */
    public function addressDetail(Request $request)
    {
        try {

            $validateRequest = Validator::make(
                $request->all(),
                [
                    'address_id' => ['required', Rule::exists('invoice_addresses', 'id')],

                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }

            $address = InvoiceAddress::find($request->address_id);

            Log::info("Invoice address saved successfully");
            successMessage(__('invoice.save_address_success'), $address->toArray());
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unkown error occured',
                'error' => $e->getMessage()
            ], 500);
            Log::error("Invoice data fetch creation failed: " . $e->getMessage());
        }
    }

    /**
     *   created by : Mikiyas Birhanu
     *   @param Request request
     *   @return Response
     */
    public function saveAddress(Request $request)
    {
        try {

            $validateRequest = Validator::make(
                $request->all(),
                [
                    'user_id' => ['required', Rule::exists('users', 'id')],
                    'state_id' => ['required', Rule::exists('states', 'id')],

                    'city_name' => ['required', 'string'],

                    'name' => 'required',
                    'email' => 'email',
                    'billing_address' => 'required',
                    'mobile_no' => 'required|numeric|digits:10',
                    'pincode' => 'numeric|digits:6',
                    'gstin' => 'string|min:15|max:15|regex:' . config('global.GST_NO_VALIDATION'),

                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }

            if (InvoiceAddress::where('user_id', $request->user_id)->first()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => "User has invoice address"
                ], 401);
            }

            $address = InvoiceAddress::create($request->all());

            Log::info("Invoice address saved successfully");
            successMessage(__('invoice.save_address_success'), $address->toArray());
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unkown error occured',
                'error' => $e->getMessage()
            ], 500);
            Log::error("Invoice data fetch creation failed: " . $e->getMessage());
        }
    }

    /**
     *   created by : Mikiyas Birhanu
     *   @param Request request
     *   @return Response
     */
    public function updateAddress(Request $request)
    {
        try {

            $validateRequest = Validator::make(
                $request->all(),
                [
                    'user_id' => ['required', Rule::exists('users', 'id')],
                    'id' => ['required', Rule::exists('invoice_addresses', 'id')->where('user_id', $request->user_id)],
                    'state_id' => ['required', Rule::exists('states', 'id')],

                    'city_name' => ['required', 'string'],

                    'name' => 'required',
                    'email' => 'email',
                    'billing_address' => 'required',
                    'mobile_no' => 'required|numeric|digits:10',
                    'pincode' => 'numeric|digits:6',
                    'gstin' => 'string|min:15|max:15|regex:' . config('global.GST_NO_VALIDATION'),

                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }

            $address = InvoiceAddress::find($request->id);
            $address->update($request->all());

            Log::info("Invoice address saved successfully");
            successMessage(__('invoice.save_address_success'), $address->toArray());
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unkown error occured',
                'error' => $e->getMessage()
            ], 500);
            Log::error("Invoice data fetch creation failed: " . $e->getMessage());
        }
    }


    /**
     *   created by : Mikiyas Birhanu
     *   @param Request request
     *   @return Response
     */
    public function detail(Request $request)
    {
        try {
            $validateRequest = Validator::make(
                $request->all(),
                [
                    'user_id' => ['required', Rule::exists('users', 'id')],
                    'invoice_id' => ['required', Rule::exists('user_invoices', 'id')->where('user_id', $request->user_id)],
                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }



            $invoiceId = $request->invoice_id;

            $invoice = UserInvoice::find($invoiceId);
            $invoice->address;
            $invoice->user;
            $invoice->subscription;
            $financialYear = (date('m') > 4) ?  date('Y') . '-' . substr((date('Y') + 1), -2) : (date('Y') - 1) . '-' . substr(date('Y'), -2);
            $invoiceDate = Carbon::now()->format('d/m/Y');
            $orderDate = Carbon::parse($invoice->created_at)->format('d/m/Y');
            $inWords = currencyConvertToWord($invoice->gst_prices->total);
            $transactionId = "";
            if ($invoice->subscription) {
                $transactionId = $invoice->subscription->transaction_id;
            } else if ($invoice->credit) {
                $transactionId = $invoice->credit->transaction_id;
            }

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




            $downloadLink = $this->getInvoicePdf($invoiceId, $result);
            $result['download_link'] = $downloadLink;
            Log::info("Invoice data fetch successfully");
            successMessage(__('invoice.info_fetch'), $result);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unkown error occured',
                'error' => $e->getMessage()
            ], 500);
            Log::error("Invoice data fetch creation failed: " . $e->getMessage());
        }
    }

    public function getInvoicePdf($invoiceId, $result)
    {

        $invoiceDir = "app/attachments";
        $storagePath =  storage_path($invoiceDir);
        $filename = 'invoice_' . $invoiceId . '.pdf';
        $filePath = $invoiceDir . '/' . $filename;

        $exists = Storage::disk('s3')->has($filePath);
        if ($exists) {
            return Storage::disk('s3')->temporaryUrl($filePath, now()->addMinutes(30));
        }

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
    /**
     *   created by : Mikiyas Birhanu
     *   @param Request request
     *   @return Response
     */
    public function creditInvoice(Request $request)
    {
        try {

            $validateRequest = Validator::make(
                $request->all(),
                [
                    'user_id' => ['required', Rule::exists('users', 'id')],
                    'invoice_id' => ['required', Rule::exists('subscription_invoices', 'id')->where('user_id', $request->user_id)],
                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }


            $invoiceId = $request->invoice_id;

            $invoice = SubscriptionInvoice::find($invoiceId);
            $invoice->address->state;
            $invoice->user;
            $invoice->subscription;
            $financialYear = (date('m') > 4) ?  date('Y') . '-' . substr((date('Y') + 1), -2) : (date('Y') - 1) . '-' . substr(date('Y'), -2);
            $invoiceDate = Carbon::now()->format('d/m/Y');
            $orderDate = Carbon::parse($invoice->created_at)->format('d/m/Y');
            $inWords = currencyConvertToWord($invoice->gst_prices->total);


            $logo = public_path() . "/backend/img/Packarma_logo.png";
            $orderFormatedId = getFormatid($invoiceId, 'orders');

            $result = [
                'invoice' => $invoice,
                'invoiceDate' => $invoiceDate,
                'orderDate' => $orderDate,
                'no_image' => $logo,
                'financialYear' => $financialYear,
                'in_words' => $inWords,
                'orderFormatedId' => $orderFormatedId
            ];
            Log::info("Invoice data fetch successfully");
            successMessage(__('invoice.info_fetch'), $result);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unkown error occured',
                'error' => $e->getMessage()
            ], 500);
            Log::error("Invoice data fetch creation failed: " . $e->getMessage());
        }
    }



    /**
     *   created by : Mikiyas Birhanu
     *   @param Request request
     *   @return Response
     */
    public function download(Request $request)
    {
        try {
            $validateRequest = Validator::make(
                $request->all(),
                [
                    'invoice_id' => ['required', 'exists:user_invoices,id']
                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }
            $invoiceId = $request->invoice_id;
            $invoice = UserInvoice::find($invoiceId);



            $invoice->address->state;
            $invoice->user;
            // return $invoice;
            $invoice->subscription;
            $invoice->invoice;
            $transactionId = $invoice->subscription ? $invoice->subscription->transaction_id : $invoice->credit->transaction_id;
            $financialYear = (date('m') > 4) ?  date('Y') . '-' . substr((date('Y') + 1), -2) : (date('Y') - 1) . '-' . substr(date('Y'), -2);
            $invoiceDate = Carbon::now()->format('d/m/Y');
            $orderDate = Carbon::parse($invoice->created_at)->format('d/m/Y');
            $inWords = currencyConvertToWord($invoice->gst_prices->total);


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
                'transactionId' => $transactionId
            ];
            // return $result;
            // return $result;

            // Generate the PDF content as a string
            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $html =  view('invoice.invoice_pdf', $result);
            $pdf->SetTitle('Order Invoice');
            $pdf->AddPage();
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdfContent = $pdf->Output('Order_Invoice.pdf', 'S');
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Order_Invoice.pdf"',
            ];

            return response($pdfContent, 200, $headers);

            // Generate a dynamic file name (e.g., based on timestamp)
            $fileName = 'subscription_' . $invoiceId . '_invoice_' . '.pdf';

            // Define the directory path
            $directory = public_path('pdfs/');

            // Check if the directory exists, if not, create it
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            // if (file_exists(public_path() . '/pdfs' . '/' . $fileName)) {
            //     return "File Exists";
            // }
            // Create a TCPDF instance
            // $pdf = new TCPDF();
            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // Set TCPDF options as needed
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Add a page
            $pdf->AddPage();

            // Load your view into the TCPDF instance
            $view = view('invoice.invoice_pdf', $result)->render();
            $pdf->writeHTML($view, true, false, true, false, '');

            // Store the generated PDF to the public directory
            $pdf->Output($directory . $fileName, 'F');

            // Optionally, you can also force the PDF to download by using the download method
            return response()->download($directory . $fileName);

            // Generate a dynamic file name (e.g., based on timestamp)
            $fileName = 'invoice_' . time() . '.pdf';

            // Define the directory path
            $directory = public_path('pdfs/');

            // Check if the directory exists, if not, create it
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            // Create a Dompdf instance
            $pdf = PDF::loadView('invoice.invoice_pdf', $result);

            // Set Dompdf options as an array
            $options = [
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                // Add more options as needed
            ];

            // Set options on the Dompdf instance
            $pdf->setOptions($options);

            // Store the generated PDF to the public directory
            $pdf->save($directory . $fileName);

            // Optionally, you can also force the PDF to download by using the download method
            return $pdf->download($fileName);

            // Generate a dynamic file name (e.g., based on timestamp)
            $fileName = 'invoice_' . time() . '.pdf';

            // Define the directory path
            $directory = public_path('pdfs/');

            // Check if the directory exists, if not, create it
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            // Create a Dompdf instance
            $pdf = PDF::loadView('invoice.invoice_pdf', $result);

            // Create Dompdf options
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);

            // Set options on the Dompdf instance
            $pdf->setOptions($options);

            // Store the generated PDF to the public directory
            $pdf->save($directory . $fileName);

            // Optionally, you can also force the PDF to download by using the download method
            return $pdf->download($fileName);

            // Generate a dynamic file name (e.g., based on timestamp)
            $fileName = 'invoice_' . time() . '.pdf';

            // Define the directory path
            $directory = public_path('pdfs/');

            // Check if the directory exists, if not, create it
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            // Create Dompdf options
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);

            // Create a Dompdf instance with options
            $pdf = PDF::setOptions($options);

            // Load your view into the Dompdf instance
            $pdf->loadView('invoice.invoice_pdf', $result);

            // Store the generated PDF to the public directory
            $pdf->save($directory . $fileName);

            // Optionally, you can also force the PDF to download by using the download method
            return $pdf->download($fileName);

            $pdf = PDF::loadView('invoice.invoice_pdf', $result);

            // Get the PDF content as a string
            $pdfContent = $pdf->output();
            // Generate a dynamic file name (e.g., based on timestamp)
            // Generate a dynamic file name (e.g., based on timestamp)
            $fileName = 'invoice_' . time() . '.pdf';

            // Define the directory path
            $directory = public_path('pdfs/');

            // Check if the directory exists, if not, create it
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            // Store the generated PDF to the public directory
            $pdf->save($directory . $fileName);

            // Optionally, you can also force the PDF to download by using the download method
            return $pdf->download($fileName);

            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $html =  view('invoice.invoice_pdf', $result);
            $pdf->SetTitle('Order Invoice');
            $pdf->AddPage();
            $pdf->writeHTML($html, true, false, true, false, '');
            // Generate the PDF content as a string
            $pdfContent = $pdf->Output('Order_Invoice.pdf', 'S');
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Order_Invoice.pdf"',
            ];

            return response($pdfContent, 200, $headers);

            // $pdf->Output('Order Invoice.pdf', 'D');
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unkown error occured',
                'error' => $e->getMessage()
            ], 500);
            Log::error("Invoice data fetch creation failed: " . $e->getMessage());
        }
    }
}
