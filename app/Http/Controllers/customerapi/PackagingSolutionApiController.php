<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecommendationEngine;
use Response;

class PackagingSolutionApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 13-05-2022
     * Uses : Display a listing of the Packaging Solution (Recommendation engine).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        try
        {
            $token = readHeaderToken();
            if($token)
            {

                $validationErrors = $this->validateRequest($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }
                else{
                    $page_no=1;
                    $limit=10;
                    if(isset($request->page_no) && !empty($request->page_no)) {
                        $page_no=$request->page_no;
                    }
                    if(isset($request->limit) && !empty($request->limit)) {
                        $limit=$request->limit;
                    }
                    $offset=($page_no-1)*$limit;

                    $data = RecommendationEngine::select('id','engine_name','structure_type','display_shelf_life')
                                                        ->where([['status','1'],['category_id',$request->category_id],['product_id', $request->product_id],['storage_condition_id',$request->storage_condition_id],['product_form_id', $request->product_form_id],['packing_type_id',$request->packing_type_id]]);
                    $engineData = RecommendationEngine::whereRaw('1 = 1');
                    if($request->engine_id)
                    {
                        $engineData = $engineData->where('id', $request->engine_id);
                        $data = $data->where('id',$request->engine_id);
                    }
                    if($request->engine_name)
                    {
                        $engineData = $engineData->where('engine_name',$request->engine_name);
                        $data = $data->where('engine_name',$request->engine_name);
                    }
                    if(empty($engineData->first()))
                    {
                        errorMessage(__('packaging_solution.packaging_solution_not_found'), $msg_data);
                    }
                    if(isset($request->search) && !empty($request->search)) {
                        $data = fullSearchQuery($data, $request->search,'engine_name|structure_type');
                    }
                    $total_records = $data->get()->count();
                    $data = $data->limit($limit)->offset($offset)->get()->toArray();
                    $responseData['result'] = $data;
                    $responseData['total_records'] = $total_records;
                    successMessage(__('success_msg.data_fetched_successfully'), $responseData);
                }
            }
            else
            {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        }
        catch(\Exception $e)
        {
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
            'packing_type_id' => 'required|numeric'
        ])->errors();
    }
}
