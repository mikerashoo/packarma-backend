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
