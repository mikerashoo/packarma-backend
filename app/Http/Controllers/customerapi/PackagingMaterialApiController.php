<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackagingMaterial;
use Illuminate\Support\Facades\DB;
use Response;

class PackagingMaterialApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 13-05-2022
     * Uses : Display a listing of the Packaging Material.
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
                // Request Validation
                $validationErrors = $this->validateMaterial($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }
                $page_no=1;
                $limit=10;
                if(isset($request->page_no) && !empty($request->page_no)) {
                    $page_no=$request->page_no;
                }
                if(isset($request->limit) && !empty($request->limit)) {
                    $limit=$request->limit;
                }
                $offset=($page_no-1)*$limit;

                $data = DB::table('recommendation_engines')->select(
                    'packaging_materials.id',
                    'packaging_materials.packaging_material_name',
                    'packaging_materials.material_description',
                    'packaging_materials.wvtr',
                    'packaging_materials.otr',
                    'packaging_materials.cof',
                    'packaging_materials.sit',
                    'packaging_materials.gsm',
                    'packaging_materials.special_feature'
                )
                    ->leftjoin('packaging_materials', 'recommendation_engines.packaging_material_id', '=', 'packaging_materials.id')
                    ->where([['recommendation_engines.id', $request->packaging_solution_id],['packaging_materials.status', '=', 1]]);

                $materialData = PackagingMaterial::whereRaw("1 = 1");

                if ($request->packaging_material_name) {
                    $materialData = $materialData->where('packaging_materials' . '' . '.packaging_material_name', $request->packaging_material_name);
                    $data = $data->where('packaging_materials' . '' . '.packaging_material_name', $request->packaging_material_name);
                }
                if ($request->material_id) {
                    $data = $data->where('packaging_materials' . '' . '.id', $request->material_id);
                }
                if(empty($materialData->first()))
                {
                    errorMessage(__('packaging_material.packaging_material_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'packaging_material_name|material_description');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                if(empty($data)) {
                    errorMessage(__('packaging_material.packaging_material_not_found'), $msg_data);
                }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            }
            else
            {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        }
        catch(\Exception $e)
        {
            \Log::error("Packaging Material fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Validate request for Customer Enquiry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    private function validateMaterial(Request $request)
    {
        return \Validator::make($request->all(), [
            'packaging_solution_id' => 'required|integer'
        ])->errors();
    }
}
