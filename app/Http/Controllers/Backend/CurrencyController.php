<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
use Yajra\DataTables\DataTables;

class CurrencyController extends Controller
{
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses :  To show currency listing page
    */
    public function index(){
        return redirect('/webadmin/dashboard');
        // $data['currency_add'] = checkPermission('currency_add');
        // $data['currency_edit'] = checkPermission('currency_edit');
        // $data['currency_view'] = checkPermission('currency_view');
        // $data['currency_status'] = checkPermission('currency_status');
        // return view('backend/currency/index',["data"=>$data]);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses :  To show data in currency datatable listing page
    */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = Currency::select('*')->orderBy('updated_at','desc');              
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {                        
                        if (isset($request['search']['search_currency_name']) && ! is_null($request['search']['search_currency_name'])) {
                            $query->where('currency_name', 'like', "%" . $request['search']['search_currency_name'] . "%");
                        }
                        if (isset($request['search']['search_currency_code']) && ! is_null($request['search']['search_currency_code'])) {
                            $query->where('currency_code', 'like', "%" . $request['search']['search_currency_code'] . "%");
                        }
                        
                        $query->get();
                    })
                    ->editColumn('currency_name', function ($event) {
	                    return $event->currency_name;
	                })
                    ->editColumn('currency_symbol', function ($event) {
	                    return $event->currency_symbol;
	                })
                    ->editColumn('currency_code', function ($event) {
	                    return $event->currency_code;
	                })
                    ->editColumn('exchange_rate', function ($event) {                        
	                    return $event->exchange_rate;
	                })
	                ->editColumn('action', function ($event) {
                        $currency_view = checkPermission('currency_view');
                        $currency_edit = checkPermission('currency_edit');
	                    $currency_status = checkPermission('currency_status');
	                    $actions = '<span style="white-space:nowrap;">';
                        if ($currency_view) {
                            $actions .= '<a href="currency_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Currency Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if($currency_edit) {
                            $actions .= ' <a href="currency_edit/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if($currency_status) {
                            if($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishCurrency" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishCurrency" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
	                }) 
	                ->addIndexColumn()
	                ->rawColumns(['currency_name','currency_symbol','currency_code','exchange_rate','action'])->setRowId('id')->make(true);
	        }
	        catch (\Exception $e) {
	    		\Log::error("Something Went Wrong. Error: " . $e->getMessage());
	    		return response([
	                'draw'            => 0,
	                'recordsTotal'    => 0,
	                'recordsFiltered' => 0,
	                'data'            => [],
	                'error'           => 'Something went wrong',
	            ]);
	    	}
        }
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses :  To store currency details in table
       *   @param Request request
       *   @return Response
    */
    public function saveFormData(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
		if (count($validationErrors)) {
            \Log::error("Currency Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if(isset($_GET['id'])) {
            $isEditFlow = true;
            $response = Currency::where([['currency_name', strtolower($request->currency_name)],['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Currency Name Already Exist', $msg_data);
            }
            $response = Currency::where([['currency_symbol', strtolower($request->currency_symbol)],['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Currency Symbol Already Exist', $msg_data);
            }
            $response = Currency::where([['currency_code', strtolower($request->currency_code)],['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Currency Code Already Exist', $msg_data);
            }
            $tblObj = Currency::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tblObj = new Currency;
            $response = Currency::where([['currency_name',strtolower($request->currency_name)]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Currency Name Already Exist', $msg_data);
            }
            $response = Currency::where([['currency_symbol',strtolower($request->currency_symbol)]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Currency Symbol Already Exist', $msg_data);
            }
            $response = Currency::where([['currency_code',strtolower($request->currency_code)]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Currency Code Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $tblObj->currency_name = ucwords($request->currency_name);
        $tblObj->currency_symbol = strtoupper($request->currency_symbol);
        $tblObj->currency_code = strtoupper($request->currency_code);
        $tblObj->exchange_rate = $request->exchange_rate;
        if($isEditFlow){
            $tblObj->updated_by = session('data')['id'];
        }else{
            $tblObj->created_by = session('data')['id'];
        }
        $tblObj->save();
        successMessage($msg , $msg_data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses : To load Add currency page
    */
    public function addCurrency() { 
        return redirect('/webadmin/dashboard');
        // $data = Currency::all();       
        // return view('backend/currency/currency_add',["data"=>$data]);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses :  To load Edit currency page
       *   @param int $id
       *   @return Response
    */

    public function editCurrency($id) {
        return redirect('/webadmin/dashboard');
    //     $data['data'] = Currency::find($id);
    //     return view('backend/currency/currency_edit',["data"=>$data]);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 05-April-2022
     *   Uses :  to load currency view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        return redirect('/webadmin/dashboard');
        // $data['data'] = Currency::find($id);
        // return view('backend/currency/currency_view', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses :  To publish or unpublish currency records
       *   @param Request request
       *   @return Response
    */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = Currency::find($request->id);
        $recordData->status = $request->status;
        $recordData->save();
        if($request->status == 1) {
        	successMessage('Published', $msg_data);
        }
        else {
        	successMessage('Unpublished', $msg_data);
        }
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses :  Currency Add|Edit Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'currency_name' => 'string|required',
	        'currency_symbol' => 'string|required',
            'currency_code' => 'string|required',
            'exchange_rate' => 'regex:/^\d+(\.\d{1,3})?$/|required',
        ])->errors();
    }

}
