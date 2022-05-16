<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Response;

class BannerApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 09-05-2022
     * Uses : Display a listing of the banner.
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
                $data = Banner::where('status','1');
                $bannerData = Banner::whereRaw("1 = 1");
                if($request->banner_id)
                {
                    $bannerData = $bannerData->where('id', $request->banner_id);
                    $data = $data->where('id',$request->banner_id);
                }
                if($request->banner_title)
                {
                    $bannerData = $bannerData->where('title',$request->banner_title);
                    $data = $data->where('title',$request->banner_title);
                }
                if(empty($bannerData->first()))
                {
                    errorMessage(__('banner.banner_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'title');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $i=0;
                foreach($data as $row)
                {
                    $data[$i]['banner_image'] = getFile($row['banner_image'], 'banner');
                    $data[$i]['banner_thumb_image'] = getFile($row['banner_thumb_image'], 'banner',false,'thumb');
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
            \Log::error("Banner fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
