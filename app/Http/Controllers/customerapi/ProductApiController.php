<?php

/**
 * Created By :Ankita Singh
 * Created On : 12 Apr 2022
 * Uses : This controller will be used for Product related APIs.
 */

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Response;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the products.
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
                $page_no = 1;
                $limit = 10;
                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;
                $data = Product::with('category', 'sub_category')->where('status', '1');
                $productData = Product::whereRaw("1 = 1");
                if ($request->product_id) {
                    $productData = $productData->where('id', $request->product_id);
                    $data = $data->where('id', $request->product_id);
                }
                if ($request->product_name) {
                    $productData = $productData->where('product_name', $request->product_name);
                    $data = $data->where('product_name', $request->product_name);
                }
                if (empty($productData->first())) {
                    errorMessage(__('product.product_not_found'), $msg_data);
                }
                // if($request->id)
                // {
                //     $data = $data->where('id',$request->id);
                // }
                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'product_name|product_description');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $i = 0;
                foreach ($data as $row) {
                    $data[$i]['product_image'] = getFile($row['product_image'], 'product');
                    $data[$i]['product_thumb_image'] = getFile($row['product_thumb_image'], 'product', false, 'thumb');
                    $i++;
                }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage('data_fetched_successfully', $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Product fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
