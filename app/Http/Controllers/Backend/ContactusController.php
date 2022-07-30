<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactEnquiry;
use App\Models\CustomerContactUs;
use App\Models\VendorContactUs;
use Yajra\DataTables\DataTables;

class ContactusController extends Controller
{
    /**
     *  created by : Shiram Mishra
     *   Created On : 23-Feb-2022
     *   Uses :  To show Contactus  listing page
     */
    public function index()
    {
        $data['data'] = array();
        $data['contact_us_view'] = checkPermission('contact_us_view');

        return view('backend/contact_us/index', $data);
    }


    /**
     *   created by : Shriram Mishra
     *   Created On : 23-Feb-2022
     *   Uses :  display dynamic data in datatable for Contactus  page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = CustomerContactUs::select('*')->orderBy('id', 'desc');

                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if ($request['search']['name'] && !is_null($request['search']['name'])) {
                            $query->where('name', 'like', "%" . $request['search']['name'] . "%");
                        }
                        if ($request['search']['email'] && !is_null($request['search']['email'])) {
                            $query->where('email', 'like', "%" . $request['search']['email'] . "%");
                        }
                        if ($request['search']['mobile'] && !is_null($request['search']['mobile'])) {
                            $query->where('mobile', 'like', "%" . $request['search']['mobile'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('name', function ($event) {
                        return $event->name;
                    })
                    ->editColumn('email', function ($event) {
                        return $event->email;
                    })

                    ->editColumn('mobile', function ($event) {
                        return $event->mobile;
                    })
                    ->editColumn('action', function ($event) {
                        $contact_us_view = checkPermission('contact_us_view');
                        $actions = '';
                        if ($contact_us_view) {
                            $actions .= ' <a href="contact_us_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name', 'email', 'mobile', 'action'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
                \Log::error("Something Went Wrong. Error: " . $e->getMessage());
                return response([
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Something went wrong',
                ]);
            }
        }
    }



    /**
     *   created by : Maaz Ansari
     *   Created On : 26-july-2022
     *   Uses :  To view user approval list details
     *   @param int $id
     *   @return Response
     */
    public function contactusView($id)
    {
        $data['data'] = CustomerContactUs::find($id);
        return view('backend/contact_us/contact_us_view', $data);
    }




    /**
     *  created by : Maaz Ansari
     *   Created On : 26-july-2022
     *   Uses :  To show Contactus  listing page
     */
    public function vendorContactus()
    {
        $data['data'] = array();
        $data['vendor_contact_us_view'] = checkPermission('vendor_contact_us_view');
        return view('backend/contact_us/vendor_contact_us', $data);
    }



    /**
     *   created by : Maaz Ansari
     *   Created On : 26-july-2022
     *   Uses :  display dynamic data in datatable for Contactus  page
     *   @param Request request
     *   @return Response
     */
    public function fetchVendorContactus(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = VendorContactUs::select('*')->orderBy('id', 'desc');

                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if ($request['search']['name'] && !is_null($request['search']['name'])) {
                            $query->where('name', 'like', "%" . $request['search']['name'] . "%");
                        }
                        if ($request['search']['email'] && !is_null($request['search']['email'])) {
                            $query->where('email', 'like', "%" . $request['search']['email'] . "%");
                        }
                        if ($request['search']['mobile'] && !is_null($request['search']['mobile'])) {
                            $query->where('mobile', 'like', "%" . $request['search']['mobile'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('name', function ($event) {
                        return $event->name;
                    })
                    ->editColumn('email', function ($event) {
                        return $event->email;
                    })

                    ->editColumn('mobile', function ($event) {
                        return $event->mobile;
                    })
                    ->editColumn('action', function ($event) {
                        $vendor_contact_us_view = checkPermission('vendor_contact_us_view');
                        $actions = '';
                        if ($vendor_contact_us_view) {
                            $actions .= ' <a href="vendor_contact_us_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name', 'email', 'mobile', 'action'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
                \Log::error("Something Went Wrong. Error: " . $e->getMessage());
                return response([
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Something went wrong',
                ]);
            }
        }
    }


    /**
     *   created by : Maaz Ansari
     *   Created On : 26-july-2022
     *   Uses :  To view user approval list details
     *   @param int $id
     *   @return Response
     */
    public function vendorContactusView($id)
    {
        $data['data'] = VendorContactUs::find($id);
        return view('backend/contact_us/vendor_contact_us_view', $data);
    }
}
