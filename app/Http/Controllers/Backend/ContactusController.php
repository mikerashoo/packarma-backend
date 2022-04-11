<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactEnquiry ;
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
                $query = ContactEnquiry::select('*');

                return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    if ($request['search']['name'] && !is_null($request['search']['name'])) {
                        $query->where('name', 'like', "%" . $request['search']['name'] . "%");
                    }
                    if ($request['search']['email'] && ! is_null($request['search']['email'])) {
                        $query->where('email', 'like', "%" . $request['search']['email'] . "%");
                    }
                    if ($request['search']['contact_no'] && ! is_null($request['search']['contact_no'])) {
                        $query->where('contact_no', 'like', "%" . $request['search']['contact_no'] . "%");
                    }
                    $query->get();
                })
                ->editColumn('name', function ($event) {
                    return $event->name;
                })
                ->editColumn('email', function ($event) {
                    return $event->email;
                })

                ->editColumn('contact_no', function ($event) {
                    return $event->contact_no;
                })
                    ->editColumn('action', function ($event) {
                    $category_edit = checkPermission('category_edit');
                    $category_status = checkPermission('category_status');
                    $actions = '';
                    if ($category_edit) {
                        $actions .= ' <a href="newsCategory_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                    }
                    if ($category_status) {
                        if ($event->status == '1') {
                            $actions .= ' <input type="checkbox" data-url=publishnewsCategory" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                        }
                        else {
                            $actions .= ' <input type="checkbox" data-url="publishnewsCategory" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                        }
                    }
                    return $actions;
                })
                    ->addIndexColumn()
                    ->rawColumns(['name','email','contact_no', 'action'])->setRowId('id')->make(true);
            }
            catch (\Exception $e) {
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
}
