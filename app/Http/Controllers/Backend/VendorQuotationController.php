<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\VendorQuotation;
use App\Models\VendorWarehouse;
use Yajra\DataTables\DataTables;

class VendorQuotationController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 13-April-2022
     *   Uses :  To show order paymennt listing page  
     */
    public function index()
    {
        $data['user'] = User::withTrashed()->Where('approval_status', '=', 'accepted')->orderBy('name', 'asc')->get();
        $data['vendor'] = Vendor::withTrashed()->Where('approval_status', '=', 'accepted')->orderBy('vendor_name', 'asc')->get();
        $data['vendor_quotation_view'] = checkPermission('vendor_quotation_view');
        return view('backend/vendors/vendor_quotation/index', ['data' => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 13-April-2022
     *   Uses :  display dynamic data in datatable for vendor quotation page  
     *   @param Request request
     *   @return Response    
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = VendorQuotation::with('user', 'vendor', 'product', 'vendor_warehouse')->orderBy('updated_at', 'desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_enquiry_id']) && !is_null($request['search']['search_enquiry_id'])) {
                            $query->where('customer_enquiry_id', $request['search']['search_enquiry_id']);
                        }
                        if (isset($request['search']['search_user_name']) && !is_null($request['search']['search_user_name'])) {
                            $query->where('user_id', $request['search']['search_user_name']);
                        }
                        if (isset($request['search']['search_vendor_name']) && !is_null($request['search']['search_vendor_name'])) {
                            $query->where('vendor_id', $request['search']['search_vendor_name']);
                        }
                        $query->get();
                    })
                    ->editColumn('user_name', function ($event) {
                        $isUserDeleted = isRecordDeleted($event->user->deleted_at);
                        if (!$isUserDeleted) {
                            return $event->user->name;
                        } else {
                            return '<span class="text-danger text-center">' . $event->user->name . '</span>';
                        }
                    })
                    ->editColumn('customer_enquiry_id', function ($event) {
                        return $event->customer_enquiry_id;
                    })
                    ->editColumn('vendor_name', function ($event) {
                        $isVendorDeleted = isRecordDeleted($event->vendor->deleted_at);
                        if (!$isVendorDeleted) {
                            return $event->vendor->vendor_name;
                        } else {
                            return '<span class="text-danger text-center">' . $event->vendor->vendor_name . '</span>';
                        }
                    })
                    ->editColumn('product_name', function ($event) {
                        return $event->product->product_name;
                    })
                    // ->editColumn('vendor_warehouse', function ($event) {
                    //     return $event->vendor_warehouse->warehouse_name;                        
                    // })
                    ->editColumn('enquiry_status', function ($event) {
                        return vendorEnquiryStatus($event->enquiry_status);
                    })
                    // ->editColumn('quotation_validity', function ($event) {
                    //     return date('d-m-Y H:i:s', strtotime($event->quotation_expiry_datetime));
                    // })
                    ->editColumn('action', function ($event) {
                        $vendor_quotation_view = checkPermission('vendor_quotation_view');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($vendor_quotation_view) {
                            $actions .= '<a href="vendor_quotation_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['user_name', 'customer_enquiry_id', 'vendor_name', 'product_name', 'enquiry_status', 'quotation_validity', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 13-April-2022
     *   Uses :  To view vendor quotation  
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['vendorEnquiryStatus'] = vendorEnquiryStatus();
        $data['data'] = VendorQuotation::with('user', 'vendor', 'product', 'vendor_warehouse')->find($id);
        return view('backend/vendors/vendor_quotation/vendor_quotation_view', $data);
    }
}
