<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use App\Models\CustomerEnquiry;
use App\Models\User;
use App\Models\UserCreditHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use stdClass;

class UserCreditController extends Controller
{
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
                    'transaction_id' => ['numeric'],
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
            $user = User::select('id', 'current_credit_amount')->where('id', $userId)->first();
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
                    'transaction_id' => $request->transaction_id ?? 0,
                    'action' => 'add'
                ]
            );

            if ($request->is_subscription) {
                $currentTotal = $currentCredit + $request->amount;

                $user->credit_totals = $currentTotal;
                $user->save();
            }
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


    public function onEnqueryResult(Request $request)
    {
        $msg_data = array();

        try {

            $validateRequest = Validator::make(
                $request->all(),
                [
                    'user_id' => ['required', Rule::exists('users', 'id')],
                    'enquery_id' => ['required', Rule::exists('customer_enquiries', 'id')],
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

            $currentCredit = $user->current_credit_amount;
            if ($currentCredit == 0) {
                errorMessage(__('my_profile.credit_limit'), $msg_data);
            }
            $data = new stdClass;

            $similarEnqueryCount = CustomerEnquiry::select(
                'id',
            )->where(
                'id',
                '!=',
                $enqueryId
            )->where([
                'user_id' => $customerEnquery->user_id,
                'category_id' => $customerEnquery->category_id,
                'sub_category_id' => $customerEnquery->sub_category_id,
                'product_id' => $customerEnquery->product_id,
                'shelf_life' => $customerEnquery->shelf_life,
                'entered_shelf_life' => $customerEnquery->entered_shelf_life,
                'entered_shelf_life_unit' => $customerEnquery->entered_shelf_life_unit,
                'product_weight' => $customerEnquery->product_weight,
                'measurement_unit_id' => $customerEnquery->measurement_unit_id,
                'product_quantity' => $customerEnquery->product_quantity,
                'storage_condition_id' => $customerEnquery->storage_condition_id,
                'packaging_machine_id' => $customerEnquery->packaging_machine_id,
                'product_form_id' => $customerEnquery->product_form_id,
                'packing_type_id' => $customerEnquery->packing_type_id,
                'packaging_treatment_id' => $customerEnquery->packaging_treatment_id,
                'recommendation_engine_id' => $customerEnquery->recommendation_engine_id,
                'packaging_material_id' => $customerEnquery->packaging_material_id,
            ])->count();
            // successMessage("no similar $similarEnqueryCount");

            if ($similarEnqueryCount && $similarEnqueryCount > 0) {
                $data->is_deducted = false;
                $data->remaining_credit = $currentCredit;
            } else {

                $remaingCredit = $currentCredit - 1;
                $user->update([
                    'current_credit_amount' => $remaingCredit
                ]);
                // $user->save();
                $userCreditHistory = UserCreditHistory::create(
                    [
                        'user_id' => $request->user_id,
                        'amount' => 1,
                        'enquery_id' => $enqueryId,
                        'reason' => __('my_profile.enquery_result_credit_deduct'),
                        'action' => 'deduct'
                    ]
                );

                $data->is_deducted = true;
                $data->remaining_credit = $remaingCredit;
            }

            // $data->enq = $customerEnquery;

            $msg_data['result'] = $data;

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
            $history = UserCreditHistory::select(
                'user_id',
                'amount',
                'reason',
                'action',
                'amount_paid',
                'expire_date',
                'created_at'
            )->where('user_id', $userId)->get();

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
