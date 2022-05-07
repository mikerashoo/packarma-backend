<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Response;

class CategoryApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 06-05-2022
     * Uses : Display a listing of the category.
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

                $data = Category::where('status','1');

                $categoryData = Category::whereRaw("1 = 1");
                if($request->category_id)
                {
                    $categoryData = $categoryData->where('id',$request->category_id);
                    $data = $data->where('id',$request->category_id);
                }
                if($request->category_name)
                {
                    $categoryData = $categoryData->where('category_name',$request->category_name);
                    $data = $data->where('category_name',$request->category_name);
                }
                if(empty($categoryData->first()))
                {
                    errorMessage(__('category.category_not_found'), $msg_data);
                }

                if(isset($request->search) && !empty($request->search)) {
                    $data = $this->fullSearchQuery($data, $request->search,'category_name');
                }

                $total_records = $data->get()->count();

                $data = $data->limit($limit)->offset($offset)->get()->toArray();

                $i=0;
                foreach($data as $row)
                {
                    $data[$i]['category_image'] = getFile($row['category_image'], 'product');
                    $data[$i]['category_thumb_image'] = getFile($row['category_thumb_image'], 'category',false,'thumb');
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
            \Log::error("Category fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * This function will be used to filter searched data.
    */
    private function fullSearchQuery($query, $word, $params)
    {
        $orwords = explode('|', $params);
        $query = $query->where(function($query) use ($word, $orwords) {
            foreach ($orwords as $key) {
                $query->orWhere($key, 'like', '%' . $word . '%');
            }
        });
        return $query;
    }
}
