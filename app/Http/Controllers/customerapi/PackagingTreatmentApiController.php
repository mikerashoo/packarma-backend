<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PackagingTreatment;
use App\Models\Product;
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
                $orderByArray = ['packaging_treatments.packaging_treatment_name' => 'ASC'];
                $defaultSortByName = false;
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
                if ($defaultSortByName) {
                    $orderByArray = ['packaging_treatments.packaging_treatment_name' => 'ASC'];
                }
                $data = allOrderBy($data, $orderByArray);
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

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 15-06-2022
     * Uses : Display a packaging treatment applicable product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function applicable_products(Request $request)
    {
        $msg_data = array();
        try
        {
            $token = readHeaderToken();
            if($token)
            {
                $page_no = 1;
                $limit = 10;
                $orderByArray = ['products.product_name' => 'ASC',];
                $defaultSortByName = false;
                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;

                $data = DB::table('products')->select(
                    'products.id',
                    'products.product_name',
                    'products.product_description',
                    'products.product_image',
                    'products.product_thumb_image',
                    'products.category_id',
                    'categories.category_name',
                    'products.sub_category_id',
                    'sub_categories.sub_category_name',
                    'products.meta_title',
                    'products.meta_description',
                    'products.meta_keyword'
                )
                    ->leftjoin('categories', 'categories.id', '=', 'products.category_id')
                    ->leftjoin('sub_categories', 'sub_categories.id', '=', 'products.sub_category_id')
                    ->where([['products.status', 1],['products.packaging_treatment_id',$request->packaging_treatment_id]]);

                $product_data = Product::whereRaw("1 = 1");

                if($request->product_id)
                {
                    $product_data = $product_data->where('products.id',$request->product_id);
                    $data = $data->where('products.id',$request->product_id);
                }
                if($request->product_name)
                {
                    $product_data = $product_data->where('products.product_name',$request->product_name);
                    $data = $data->where('products.product_name',$request->product_name);
                }
                if($request->category_id)
                {
                    $product_data = $product_data->where('products.category_id',$request->category_id);
                    $data = $data->where('products.category_id',$request->category_id);
                }
                if($request->sub_category_id)
                {
                    $product_data = $product_data->where('products.sub_category_id',$request->sub_category_id);
                    $data = $data->where('products.sub_category_id',$request->sub_category_id);
                }
                if(empty($product_data->first()))
                {
                    errorMessage(__('packaging_treatment.treatment_applicable_product_not_found'), $msg_data);
                }
                if ($defaultSortByName) {
                    $orderByArray = ['products.product_name' => 'ASC'];
                }
                $data = allOrderBy($data, $orderByArray);
                $total_records = $data->get()->count();
                $data = $data->get()->toArray();
                $i=0;
                foreach($data as $row)
                {
                    $data[$i]->product_image = getFile($row->product_image, 'product');
                    $data[$i]->product_thumb_image = getFile($row->product_thumb_image, 'product',false,'thumb');
                    $i++;
                }
                if(empty($data)) {
                    errorMessage(__('packaging_treatment.treatment_applicable_product_not_found'), $msg_data);
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
            \Log::error("Packaging Treatment Applicable Product fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
