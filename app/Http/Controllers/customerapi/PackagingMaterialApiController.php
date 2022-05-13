<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackagingMaterial;
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
                $page_no=1;
                $limit=10;
                if(isset($request->page_no) && !empty($request->page_no)) {
                    $page_no=$request->page_no;
                }
                if(isset($request->limit) && !empty($request->limit)) {
                    $limit=$request->limit;
                }
                $offset=($page_no-1)*$limit;
                $data = PackagingMaterial::where('status','1');
                $materialData = PackagingMaterial::whereRaw("1 = 1");
                if($request->material_id)
                {
                    $materialData = $materialData->where('id', $request->material_id);
                    $data = $data->where('id',$request->material_id);
                }
                if($request->material_name)
                {
                    $materialData = $materialData->where('packaging_material_name',$request->material_name);
                    $data = $data->where('packaging_material_name',$request->material_name);
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
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage('data_fetched_successfully', $responseData);
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
}
