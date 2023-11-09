<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\BannerClick;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
        try {
            $token = readHeaderToken();
            if ($token) {
                $data = array();
                $page_no = 1;
                $limit = 10;
                $orderByArray = ['banners.updated_at' => 'DESC',];
                $defaultSortByName = false;
                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;
                $data = Banner::select('id', 'title', 'link', 'description', 'banner_image', 'banner_thumb_image', 'seo_url', 'meta_title', 'meta_description', 'meta_keyword')->where('status', '1');
                $bannerData = Banner::whereRaw("1 = 1");
                if ($request->banner_id) {
                    $bannerData = $bannerData->where('id', $request->banner_id);
                    $data = $data->where('id', $request->banner_id);
                }
                if ($request->banner_title) {
                    $bannerData = $bannerData->where('title', $request->banner_title);
                    $data = $data->where('title', $request->banner_title);
                }
                if (empty($bannerData->first())) {
                    errorMessage(__('banner.banner_not_found'), $msg_data);
                }
                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'title');
                }
                if ($defaultSortByName) {
                    $orderByArray = ['products.product_name' => 'ASC'];
                }
                $data = allOrderBy($data, $orderByArray);
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $i = 0;
                foreach ($data as $row) {
                    $data[$i]['banner_image'] = getFile($row['banner_image'], 'banner');
                    $data[$i]['banner_thumb_image'] = getFile($row['banner_thumb_image'], 'banner', false, 'thumb');
                    $i++;
                }
                if (empty($data)) {
                    $data[0]['banner_image'] = getFile('banner_image', 'banner');
                    $data[0]['banner_thumb_image'] = getFile('banner_thumb_image', 'banner', false, 'thumb');
                    // errorMessage(__('banner.banner_not_found'), $msg_data);
                }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Banner fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 09-05-2022
     * Uses : Display a listing of the banner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveClick(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $validateRequest = Validator::make(
                    $request->all(),
                    [
                        'user_id' => ['required', Rule::exists('users', 'id')],
                        'banner_id' => [Rule::exists('banners', 'id'), 'required_without:solution_banner_id'],
                        'solution_banner_id' => ['required_without:banner_id', Rule::exists('solution_banners', 'id')],
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
                $bannerId = $request->banner_id;
                $solutionId = $request->solution_banner_id;
                if ($bannerId) {
                    $bannerClick = BannerClick::ofUser($userId)->ofBanner($bannerId)->first();
                    if (!$bannerClick) {
                        $bannerClick = new BannerClick();
                        $bannerClick->user_id = $userId;
                        $bannerClick->banner_id = $bannerId;
                        $bannerClick->save();
                    }
                } else {
                    $bannerClick = BannerClick::ofUser($userId)->ofSolutionBanner($solutionId)->first();
                    if (!$bannerClick) {
                        $bannerClick = new BannerClick();
                        $bannerClick->user_id = $userId;
                        $bannerClick->solution_banner_id = $solutionId;
                        $bannerClick->save();
                    }
                }

                $responseData['result'] = $bannerClick;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unkown error occured',
                'error' => $e->getMessage()
            ], 500);
            \Log::error("Banner fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
