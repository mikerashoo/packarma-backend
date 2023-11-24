<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use App\Models\CustomerEnquiry;
use App\Models\GeneralSetting;
use App\Models\RecommendationEngine;
use App\Models\User;
use App\Models\UserCreditHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use stdClass;

class UserCreditController extends Controller
{
    public function creditPrice(Request $request)
    {
        $msg_data = array();
        try {
            $data = new stdClass;
            $creditPrice = GeneralSetting::ofCreditPrice()->first();
            $data->credit_price = $creditPrice ? $creditPrice->value : 0;

            $creditPercent = GeneralSetting::ofCreditPercent()->first();
            $data->credit_price = $creditPrice ? $creditPrice->value : 0;
            $data->credit_percent= $creditPercent ? $creditPercent->value : 0;
            $msg_data['result'] = $data;
            successMessage(__('my_profile.credits_fetch'), $msg_data);
        } catch (\Exception $e) {

            Log::error("Adding credit failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
        // return $user;,
    }
 public function index(Request $request)
    {
        $msg_data = array();
        try {
            $validateRequest = Validator::make(
                $request->all(),
                [
                    'user_id' => ['required', Rule::exists('users', 'id')]
                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }


            $userId = $request->user_id;
            $data = User::select('current_credit_amount', 'credit_totals', DB::raw('subscription_end AS expire_date'))->where('id', $userId)->first();

            if ($data) {
                $isSubscripitonActive = false;

                if ($data->expire_date && !Carbon::parse($data->expire_date)->isPast()) {
                    $isSubscripitonActive = true;
                }

                $data->is_subscription_active = $isSubscripitonActive;
            }



            $msg_data['result'] = $data;
            successMessage(__('my_profile.credits_fetch'), $msg_data);
        } catch (\Exception $e) {

            Log::error("Adding credit failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
        // return $user;,
    }

    public function addCredits(Request $request)
    {
        $msg_data = array();

        try {

            $validateRequest = Validator::make(
                $request->all(),
                [
                    'user_id' => ['required', Rule::exists('users', 'id')],
                    'amount' => ['required', 'numeric', 'min:0'],
                    'amount_paid' => ['required', 'numeric', 'min:0'],
                    'expire_date' => ['required', 'date'],
                    'reason' => 'required',
                    'is_subscription' => 'bool'
                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }


            $userId = $request->user_id;
            $user = User::select('id', 'current_credit_amount', 'credit_totals')->where('id', $userId)->first();
            $currentCredit = $user->current_credit_amount;
            $credits = $currentCredit + $request->amount;
            $user->update([
                'current_credit_amount' => $credits
            ]);
            // $user->save();
            $userCreditHistory = UserCreditHistory::create(
                [
                    'user_id' => $request->user_id,
                    'amount' => $request->amount,
                    'reason' => $request->reason,
                    'amount_paid' => $request->amount_paid,
                    'expire_date' => $request->expire_date,
                    'transaction_id' => $request->transaction_id,
                    'action' => 'add'
                ]
            );

            $currentTotal = $request->is_subscription ? $currentCredit : $user->credit_totals;

            $newTotal = $currentTotal + $request->amount;

            $user->credit_totals = $newTotal;
            $user->save();

            $data = new stdClass;

            $data->credit_history = $userCreditHistory;
            $data->credit_amount_before = $currentCredit;
            $data->credit_amount_now = $credits;
            $msg_data['result'] = $data;
            successMessage(__('my_profile.credits_added'), $msg_data);
        } catch (\Exception $e) {

            Log::error("Adding credit failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
        // return $user;,
    }

    public function deductCredit(Request $request)
    {
        $msg_data = array();

        try {

            $validateRequest = Validator::make(
                $request->all(),
                [
                    'user_id' => ['required', Rule::exists('users', 'id')],
                    'ids' => 'required|array',
                    'ids.*' => 'exists:recommendation_engines,id',
                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }

            $t = CustomerEnquiry::first();
            // $t->recommendationEngines()->attach($request->ids);
            // 'recommendation_engines.engine_name',
            // 'recommendation_engines.structure_type',
            // 'recommendation_engines.display_shelf_life',
            // 'recommendation_engines.min_order_quantity',
            // 'recommendation_engines.min_order_quantity_unit',
            return $t->recommendationEngines()->select(['engine_name', 'structure_type', 'display_shelf_life', 'min_order_quantity', 'min_order_quantity_unit'])->get();
            $userId = $request->user_id;
            $enqueryId = $request->enquery_id;
            $user = User::select('id', 'current_credit_amount')->where('id', $userId)->first();

            $currentCredit = $user->current_credit_amount;
            if ($currentCredit == 0) {
                errorMessage(__('my_profile.credit_limit'), $msg_data);
            }
            $data = new stdClass;



            $remaingCredit = $currentCredit - 1;
            $user->update([
                'current_credit_amount' => $remaingCredit
            ]);
            // $user->save();
            $userCreditHistory = UserCreditHistory::create(
                [
                    'user_id' => $request->user_id,
                    'amount' => 1,
                    'reason' => __('my_profile.enquery_result_credit_deduct'),
                    'action' => 'deduct'
                ]
            );

            $data->is_deducted = true;
            $data->remaining_credit = $remaingCredit;
            $data->credit = $userCreditHistory;


            // $data->enq = $customerEnquery;

            $msg_data['result'] = $data;

            successMessage(__('my_profile.credit_deduct'), $msg_data);
        } catch (\Exception $e) {

            Log::error("Adding credit failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
        // return $user;,
    }


    public function onEnqueryResult(Request $request)
    {
        $msg_data = array();

        try {

            $validateRequest = Validator::make(
                $request->all(),
                [
                    'user_id' => ['required', Rule::exists('users', 'id')],
                    'enquery_id' => ['required', Rule::exists('customer_enquiries', 'id')],
                    'credit_id' => ['required', Rule::exists('user_credit_histories', 'id')],
                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }


            $userId = $request->user_id;
            $enqueryId = $request->enquery_id;
            $creditId = $request->credit_id;

            $credit = UserCreditHistory::find($creditId);
            if ($credit->user_id != $userId) {
                errorMessage(__('my_profile.invalid_credit_id'), $msg_data);
            }

            if ($credit->enquery_id != null) {
                errorMessage(__('my_profile.credit_enquery_exists_id'), $msg_data);
            }
            $user = User::select('id', 'current_credit_amount')->where('id', $userId)->first();
            $customerEnquery = CustomerEnquiry::select(
                'id',
                'user_id',
                'category_id',
                'sub_category_id',
                'product_id',
                'shelf_life',
                'entered_shelf_life',
                'entered_shelf_life_unit',
                'product_weight',
                'measurement_unit_id',
                'product_quantity',
                'storage_condition_id',
                'packaging_machine_id',
                'product_form_id',
                'packing_type_id',
                'packaging_treatment_id',
                'recommendation_engine_id',
                'packaging_material_id',
            )->where('id', $enqueryId)->first();

            if ($customerEnquery->user_id != $userId) {
                errorMessage(__('my_profile.invalid_enquery_id'), $msg_data);
            }
            $credit->enquery_id = $enqueryId;
            $credit->save();


            // $data->enq = $customerEnquery;

            $msg_data['result'] = $credit;

            successMessage(__('my_profile.credit_deduct'), $msg_data);
        } catch (\Exception $e) {

            Log::error("Adding credit failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
        // return $user;,
    }

    public function creditHistory(Request $request)
    {
        $msg_data = array();

        try {

            $validateRequest = Validator::make(
                $request->all(),
                [
                    'user_id' => ['required', Rule::exists('users', 'id')],
                ],
            );

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 401);
            }


            $userId = $request->user_id;
            $enqueryId = $request->enquery_id;
            $user = User::select('id', 'current_credit_amount')->where('id', $userId)->first();
            $history = UserCreditHistory::where('user_id', $userId)->get();

            // $data->enq = $customerEnquery;

            $msg_data['result'] = $history;

            successMessage(__('my_profile.credits_history_fetch'), $msg_data);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unkown error occured',
                'error' => $e->getMessage()
            ], 500);
            Log::error("Adding credit failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
        // return $user;,
    }
}
