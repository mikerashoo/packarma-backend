<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Subscription;
use App\Models\SubscriptionBenefit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SubscriptionBenefitController extends Controller
{
    /**
     *   created by : Mikiyas Birhanu
     *   Created On : 20-Oct-2023
     *   Uses :  To show subscription benefit listing page
     */
    public function index($id)
    {
        $benefits = SubscriptionBenefit::ofSubscription($id)->get();
        $data['benefit_edit'] = checkPermission('subscription_edit');
        $data['benefits'] = $benefits;
        $data['id'] = $id;
        return view('backend/subscription/benefits/index', ["data" => $data]);
    }

    /**
     *   created by : Mikiyas Birhanu
     *   Created On : 20-Oct-2023
     *   Uses :  display dynamic data in datatable for subscription benefits page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request, $id)
    {


        if ($request->ajax()) {
            try {
                $query = SubscriptionBenefit::ofSubscription($id);

                return DataTables::of($query)
                    ->editColumn('description', function ($event) {
                        return $event->description;
                    })
                    ->editColumn('updated_at', function ($event) {
                        return date('d-m-Y h:i A', strtotime($event->updated_at));
                    })
                    ->addIndexColumn()
                    ->rawColumns(['description',  'updated_at'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
                Log::error("Something Went Wrong. Error: " . $e->getMessage());
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
     *   created by : Mikiyas Birhanu
     *   Created On : 20-Oct-2023
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
     *   created by : Mikiyas Birhanu
     *   Created On : 20-Oct-2023
     *   Uses :  To store subscription data in table
     *   @param Request request
     *   @return Response
     */
    public function add(Request $request, $id)
    {

        $validateRequest = Validator::make(
            $request->all(),
                [
                    'benefits' => 'array'
                ],
        );

        if ($validateRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateRequest->errors()
            ], 401);
        }
        $msg = "Benefit added successfully";
        $subscription = Subscription::find($id);
        $subscription->benefits()->delete();

        $benefits = [];

        $descriptions = $request->benefits;
        foreach ($descriptions as $description){
            array_push($benefits, ['description' => $description]);
        };

        $subscription->benefits()->createMany($benefits);



        $benefits = SubscriptionBenefit::ofSubscription($id)->get();
        $data['benefit_edit'] = checkPermission('subscription_edit');
        $data['benefits'] = $benefits;
        $data['id'] = $id;
        return redirect()->route('subscription.list')->withSuccess( $msg);



        $msg_data = array();

        successMessage($msg, $msg_data);


    }

         /**
     *   created by : Mikiyas Birhanu
     *   Created On : 20-Oct-2023
     *   Uses :  To store subscription data in table
     *   @param Request request
     *   @return Response
     */
    public function delete($id, $benefitId)
    {

        $benefit = SubscriptionBenefit::find($benefitId);
        $msg_data = array();
        if(!$benefit){
            errorMessage('Invalid benefit id', $msg_data);
        }
        $msg = "Benefit deleted successfully";

        $benefit->delete();
        $msg_data = array();
        return redirect()->back()->with('message', $msg);

        return redirect()->route('subscription_benefits.list', ['id' => $id])->with('message', $msg);

        // return redirect()->back()->with('message', 'IT WORKS!');
        successMessage($msg, $msg_data);
    }

    /**
     *   created by : Mikiyas Birhanu
     *   Created On : 20-Oct-2023
     *   Uses :  Subscription Form Validation part will be handle by below function
     *   @param Request request
     *   @return Countable|array
     */
    private function validateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'amount' => 'sometimes|required|numeric|gt:.99',
            'duration' => 'sometimes|required|integer|gt:0',
        ])->errors();
    }
}
