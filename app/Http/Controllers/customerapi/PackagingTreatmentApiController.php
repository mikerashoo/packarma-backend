<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackagingTreatment;
use Response;

class PackagingTreatmentApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 09-05-2022
     * Uses : Display a listing of the packaging treatment.
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

                $data = PackagingTreatment::select('id','packaging_treatment_name','packaging_treatment_description','packaging_treatment_image','packaging_treatment_thumb_image','meta_title','meta_description','meta_keyword')->where('status','1');

                $treatmentData = PackagingTreatment::whereRaw("1 = 1");
                if($request->treatment_id)
                {
                    $treatmentData = $treatmentData->where('id',$request->treatment_id);
                    $data = $data->where('id',$request->treatment_id);
                }
                if($request->treatment_name)
                {
                    $treatmentData = $treatmentData->where('packaging_treatment_name',$request->treatment_name);
                    $data = $data->where('packaging_treatment_name',$request->treatment_name);
                }
                if(empty($treatmentData->first()))
                {
                    errorMessage(__('packaging_treatment.packaging_treatment_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'packaging_treatment_name|packaging_treatment_description');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $i=0;
                foreach($data as $row)
                {
                    $data[$i]['packaging_treatment_image'] = getFile($row['packaging_treatment_image'], 'packaging_treatment');
                    $data[$i]['packaging_treatment_thumb_image'] = getFile($row['packaging_treatment_thumb_image'], 'packaging_treatment',false,'thumb');
                    $i++;
                }
                if(empty($data)) {
                    errorMessage(__('packaging_treatment.packaging_treatment_not_found'), $msg_data);
                }
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
            \Log::error("Packaging Treatment fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 18-05-2022
     * Uses : Display a listing of the packaging treatment which is featured.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function featured_index(Request $request)
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

                $featureData = PackagingTreatment::select('id','packaging_treatment_name','packaging_treatment_description','packaging_treatment_image','packaging_treatment_thumb_image','meta_title','meta_description','meta_keyword')
                                ->where([['status','1'],['is_featured','1']]);
                $featuredTreatmentData = PackagingTreatment::whereRaw("1 = 1");
                // print_r($featuredTreatmentData);exit;

                if($request->treatment_id)
                {
                    $featuredTreatmentData = $featuredTreatmentData->where('id',$request->treatment_id);
                    $featureData = $featureData->where('id',$request->treatment_id);
                }
                if($request->treatment_name)
                {
                    $featuredTreatmentData = $featuredTreatmentData->where('packaging_treatment_name',$request->treatment_name);
                    $featureData = $featureData->where('packaging_treatment_name',$request->treatment_name);
                }
                if(empty($featureData->first()))
                {
                    errorMessage(__('packaging_treatment.packaging_treatment_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $featureData = fullSearchQuery($featureData, $request->search,'packaging_treatment_name|packaging_treatment_description');
                }
                $total_records = $featureData->get()->count();
                $featureData = $featureData->limit($limit)->offset($offset)->get()->toArray();
                $i=0;
                foreach($featureData as $row)
                {
                    $featureData[$i]['packaging_treatment_image'] = getFile($row['packaging_treatment_image'], 'packaging_treatment');
                    $featureData[$i]['packaging_treatment_thumb_image'] = getFile($row['packaging_treatment_thumb_image'], 'packaging_treatment',false,'thumb');
                    $i++;
                }
                if(empty($featureData)) {
                    errorMessage(__('packaging_treatment.packaging_treatment_not_found'), $msg_data);
                }
                $responseData['result'] = $featureData;
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
            \Log::error("Packaging Treatment Featured fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
