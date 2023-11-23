<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Subscription;
use Yajra\DataTables\DataTables;

class SubscriptionController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 01-April-2022
     *   Uses :  To show subscription listing page
     */
    public function index()
    {
        $data['subscription_edit'] = checkPermission('subscription_edit');
        $data['type'] = subscriptionType();
        return view('backend/subscription/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 01-April-2022
     *   Uses :  display dynamic data in datatable for subscription page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Subscription::select('*')->orderBy('updated_at', 'desc');

                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_type']) && !is_null($request['search']['search_type'])) {
                            $query->where('subscription_type', 'like', "%" . $request['search']['search_type'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('type', function ($event) {
                        // print_r($event);exit;
                        return subscriptionType($event->subscription_type);
                    })
                    ->editColumn('amount', function ($event) {
                        return $event->amount;
                    })
                    ->editColumn('credit_amount', function ($event) {
                        return $event->credit_amount;
                    })
                    ->editColumn('updated_at', function ($event) {
                        return date('d-m-Y h:i A', strtotime($event->updated_at));
                    })
                    ->editColumn('status', function ($event) {
                        $subscription_benefits = checkPermission('subscription_edit');
                        $subscription_edit = checkPermission('subscription_edit');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($subscription_edit) {
                            $actions .= ' <a href="subscriptionEdit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                            $actions .= ' <a href=' . route("subscription_benefits.list", ["id" => $event->id]) .' class="btn btn-warning btn-sm src_data" title="Benefits"><i class="fa fa-list"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['type', 'amount', 'updated_at', 'status'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
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
     *   Created On : 01-April-2022
     *   Uses :  To load Edit subscription page
     *   @param int $id
     *   @return Response
     */
    public function editSubscription($id)
    {
        $data['data'] = Subscription::find($id);
        $data['type'] = subscriptionType();
        return view('backend/subscription/subscription_update', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 01-April-2022
     *   Uses :  To store subscription data in table
     *   @param Request request
     *   @return Response
     */
    public function updateSubscription(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
        if (count($validationErrors)) {
            \Log::error("Subscription Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        if (isset($_GET['id'])) {
            $tableObject = Subscription::find($_GET['id']);
            $msg = "Subscription Amount Updated Successfully";
        }
        $tableObject->amount = !empty($request->amount) ? $request->amount : 0.00;
        $tableObject->credit_amount = !empty($request->credit_amount) ? $request->credit_amount : 0.00;
        $tableObject->updated_at = date('Y-m-d H:i:s');
        $tableObject->updated_by =  session('data')['id'];

        if ($request->duration) {
            $tableObject->duration = $request->duration;
        }
        $tableObject->save();
        successMessage($msg, $msg_data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 01-April-2022
     *   Uses :  Subscription Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'amount' => 'sometimes|required|numeric|gt:.99',
            'duration' => 'sometimes|required|integer|gt:0',
            'credit_amount' => 'sometimes|integer|gt:0'
        ])->errors();
    }
}
