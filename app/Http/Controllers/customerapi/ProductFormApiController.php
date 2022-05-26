<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductForm;
use Response;

class ProductFormApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 12-05-2022
     * Uses : Display a listing of the Product Form.
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
                $data = ProductForm::select('id', 'product_form_name','short_description','product_form_image','product_form_thumb_image','meta_title','meta_description','meta_keyword')->where('status','1');
                $productFormData = ProductForm::whereRaw("1 = 1");
                if($request->form_id)
                {
                    $productFormData = $productFormData->where('id', $request->form_id);
                    $data = $data->where('id',$request->form_id);
                }
                if($request->form_name)
                {
                    $productFormData = $productFormData->where('product_form_name',$request->form_name);
                    $data = $data->where('product_form_name',$request->form_name);
                }
                if(empty($productFormData->first()))
                {
                    errorMessage(__('product_form.product_form_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'product_form_name|short_description');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $i=0;
                foreach($data as $row)
                {
                    $data[$i]['product_form_image'] = getFile($row['product_form_image'], 'product_form');
                    $data[$i]['product_form_thumb_image'] = getFile($row['product_form_thumb_image'], 'product_form',false,'thumb');
                    $i++;
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
            \Log::error("Product Form fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
