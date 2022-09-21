<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecommendationEngine;
use App\Models\User;
use Carbon\Carbon;
use Response;

class PackagingSolutionApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 13-05-2022
     * Uses : Display a listing of the Packaging Solution (Recommendation engine) based on required parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        $isSubscribed = true;
        $placeEnquiry = true;

        try {
            $token = readHeaderToken();
            if ($token) {

                $validationErrors = $this->validateRequest($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                } else {
                    $page_no = 1;
                    $limit = 10;
                    $orderByArray = ['recommendation_engines.engine_name' => 'ASC'];
                    $defaultSortByName = false;
                    $user_id = $token['sub'];
                    $userSubscriptionCheck = User::find($user_id);
                    $subscriptionEndDate = $userSubscriptionCheck->subscription_end;
                    $todaysDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());


                    if (($userSubscriptionCheck->subscription_id == 0) || ($subscriptionEndDate < $todaysDate)) {
                        $isSubscribed = false;
                        $msg_data['is_subscribed'] = $isSubscribed;
                        errorMessage(__('user.no_active_subscription'), $msg_data);
                    }

                    if (isset($request->page_no) && !empty($request->page_no)) {
                        $page_no = $request->page_no;
                    }
                    if (isset($request->limit) && !empty($request->limit)) {
                        $limit = $request->limit;
                    }
                    $offset = ($page_no - 1) * $limit;

                    // $data = RecommendationEngine::select('id', 'engine_name', 'structure_type', 'display_shelf_life')
                    $data = RecommendationEngine::with('packaging_material')
                        ->where([['status', '1'], ['category_id', $request->category_id], ['product_id', $request->product_id], ['storage_condition_id', $request->storage_condition_id], ['product_form_id', $request->product_form_id], ['packing_type_id', $request->packing_type_id], ['display_shelf_life', '>=', $request->shelf_life]]);
                    $engineData = RecommendationEngine::whereRaw('1 = 1');
                    if ($request->engine_id) {
                        $engineData = $engineData->where('id', $request->engine_id);
                        $data = $data->where('id', $request->engine_id);
                    }
                    if ($request->engine_name) {
                        $engineData = $engineData->where('engine_name', $request->engine_name);
                        $data = $data->where('engine_name', $request->engine_name);
                    }

                    if (empty($engineData->first())) {
                        errorMessage(__('packaging_solution.packaging_solution_not_found'), $msg_data);
                    }
                    if (isset($request->search) && !empty($request->search)) {
                        $data = fullSearchQuery($data, $request->search, 'engine_name|structure_type');
                    }
                    if ($defaultSortByName) {
                        $orderByArray = ['recommendation_engines.engine_name' => 'ASC'];
                    }
                    $data = allOrderBy($data, $orderByArray);
                    $total_records = $data->get()->count();
                    $data = $data->limit($limit)->offset($offset)->get()->toArray();
                    if (empty($data)) {
                        $placeEnquiry = false;
                        $msg_data['is_subscribed'] = $isSubscribed;
                        $msg_data['place_enquiry'] = $placeEnquiry;
                        errorMessage(__('packaging_solution.packaging_solution_not_found'), $msg_data);
                    }

                    $responseData['result'] = $data;
                    $responseData['is_subscribed'] = $isSubscribed;
                    $responseData['place_enquiry'] = $placeEnquiry;
                    $responseData['total_records'] = $total_records;
                    successMessage(__('success_msg.data_fetched_successfully'), $responseData);
                }
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Packaging Solution fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 17/05/2022
     * Uses : Validate request for packaging solution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'storage_condition_id' => 'required|numeric',
            'product_form_id' => 'required|numeric',
            'packing_type_id' => 'required|numeric',
            'shelf_life' => 'required|integer'
        ])->errors();
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 20-Sept-2022
     * Uses : Display a listing of the alternatve Packaging Solution (Recommendation engine) based on required parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function alternativeSolutions(Request $request)
    {
        $msg_data = array();
        $isSubscribed = true;
        $placeEnquiry = true;

        try {
            $token = readHeaderToken();
            if ($token) {

                $validationErrors = $this->validateAlternativeRequest($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                } else {
                    $page_no = 1;
                    $limit = 10;
                    $orderByArray = ['recommendation_engines.engine_name' => 'ASC'];
                    $defaultSortByName = false;
                    $user_id = $token['sub'];
                    $userSubscriptionCheck = User::find($user_id);
                    $subscriptionEndDate = $userSubscriptionCheck->subscription_end;
                    $todaysDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());


                    if (($userSubscriptionCheck->subscription_id == 0) || ($subscriptionEndDate < $todaysDate)) {
                        $isSubscribed = false;
                        $msg_data['is_subscribed'] = $isSubscribed;
                        errorMessage(__('user.no_active_subscription'), $msg_data);
                    }

                    if (isset($request->page_no) && !empty($request->page_no)) {
                        $page_no = $request->page_no;
                    }
                    if (isset($request->limit) && !empty($request->limit)) {
                        $limit = $request->limit;
                    }
                    $offset = ($page_no - 1) * $limit;

                    $data = RecommendationEngine::with('packaging_material')
                        ->where([['status', '1'],['product_id', $request->product_id]]);
                    $engineData = RecommendationEngine::whereRaw('1 = 1');
                    if ($request->engine_id) {
                        $engineData = $engineData->where('id', $request->engine_id);
                        $data = $data->where('id', $request->engine_id);
                    }
                    if ($request->engine_name) {
                        $engineData = $engineData->where('engine_name', $request->engine_name);
                        $data = $data->where('engine_name', $request->engine_name);
                    }

                    if (empty($engineData->first())) {
                        errorMessage(__('packaging_solution.packaging_solution_not_found'), $msg_data);
                    }
                    if (isset($request->search) && !empty($request->search)) {
                        $data = fullSearchQuery($data, $request->search, 'engine_name|structure_type');
                    }
                    if ($defaultSortByName) {
                        $orderByArray = ['recommendation_engines.engine_name' => 'ASC'];
                    }
                    $data = allOrderBy($data, $orderByArray);
                    $total_records = $data->get()->count();
                    $data = $data->limit($limit)->offset($offset)->get()->toArray();
                    if (empty($data)) {
                        $placeEnquiry = false;
                        $msg_data['is_subscribed'] = $isSubscribed;
                        $msg_data['place_enquiry'] = $placeEnquiry;
                        errorMessage(__('packaging_solution.packaging_solution_not_found'), $msg_data);
                    }

                    $responseData['result'] = $data;
                    $responseData['is_subscribed'] = $isSubscribed;
                    $responseData['place_enquiry'] = $placeEnquiry;
                    $responseData['total_records'] = $total_records;
                    successMessage(__('success_msg.data_fetched_successfully'), $responseData);
                }
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Packaging Solution fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 20-Sept-2022
     * Uses : Validate request for alternative packaging solution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateAlternativeRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'product_id' => 'required|integer',
        ])->errors();
    }
}
