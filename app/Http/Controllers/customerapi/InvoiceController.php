<?php

/**
 * Created By :Mikiyas Birhanu
 * Created On : 02 Nov 2024
 * Uses : This controller will be used to invoice .
 */

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\CustomerDevice;
use App\Models\SubscriptionInvoice;
use App\Models\UserAddress;
use App\Models\UserSubscriptionPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Session;
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
                    'user_address_id' => ['required', Rule::exists('user_addresses', 'id')->where('user_id', $request->user_id)],
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
            $addressId = $request->user_address_id;

            $invoice = SubscriptionInvoice::create([
                'user_id' => $userId,
                'user_subscription_id' => $userSubscriptionPaymentId,
                'user_address_id' => $addressId,
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
    public function detail(Request $request)
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
            // $invoice->address;

            // $userAddress = UserAddress::find($invoice->address_id);

            // $invoice->billing_address = $userAddress;

            Log::info("Invoice data fetch successfully");
            successMessage(__('invoice.info_fetch'), $invoice->toArray());
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
