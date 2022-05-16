<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Response;

class SubCategoryApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 09-05-2022
     * Uses : Display a listing of the sub category.
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

                $data = SubCategory::where('status','1');

                $subCategoryData = SubCategory::whereRaw("1 = 1");
                if($request->sub_category_id)
                {
                    $subCategoryData = $subCategoryData->where('id',$request->sub_category_id);
                    $data = $data->where('id',$request->sub_category_id);
                }
                if($request->sub_category_name)
                {
                    $subCategoryData = $subCategoryData->where('sub_category_name',$request->sub_category_name);
                    $data = $data->where('sub_category_name',$request->sub_category_name);
                }
                if(empty($subCategoryData->first()))
                {
                    errorMessage(__('sub_category.sub_category_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'sub_category_name');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $i=0;
                foreach($data as $row)
                {
                    $data[$i]['sub_category_image'] = getFile($row['sub_category_image'], 'sub_category');
                    $data[$i]['sub_category_thumb_image'] = getFile($row['sub_category_thumb_image'], 'sub_category',false,'thumb');
                    $i++;
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
            \Log::error("Sub Category fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
