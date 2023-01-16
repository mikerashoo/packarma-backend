<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Exports\ExportCustomerReport;
use App\Exports\ExportEnquiryReport;
use App\Exports\ExportOrderReport;
use App\Exports\ExportVendorQuotationReport;
use App\Models\PackagingMaterial;
use App\Models\Product;
use App\Models\RecommendationEngine;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function customerReportForm(){
        $data['customers'] = User::where('status',1)->get();
        return view('backend/report/customer_report_form',$data);
    }
    public function enquiryReportForm(){
        $data['customers'] = User::where('status',1)->where('approval_status','accepted')->get();
        $data['recommendation_engines'] = RecommendationEngine::where('status',1)->get();
        $data['products'] = Product::with('category','sub_category')->where('status',1)->get();
        $data['packaging_material'] = PackagingMaterial::where('status',1)->get();
        return view('backend/report/enquiry_report_form',$data);
    }
    public function vendorQuotationReportForm(){
        $data['vendors'] = Vendor::where('status',1)->where('approval_status','accepted')->get();
        $data['packaging_materials'] = PackagingMaterial::where('status',1)->get();
        return view('backend/report/vendor_quotation_report_form',$data);
    }
    public function orderReportForm(){
        $data['customers'] = User::where('status',1)->where('approval_status','accepted')->get();
        $data['products'] = Product::with('category','sub_category')->where('status',1)->get();
        $data['vendors'] = Vendor::all();
        $data['recommendation_engines'] = RecommendationEngine::where('status',1)->get();
        return view('backend/report/order_report_form',$data);
    }
    public function exportEnquiryReport(Request $request){

        return Excel::download(new ExportEnquiryReport($request), 'Enquiry-' . Carbon::now()->format('Y-m-d') .'.xlsx');
    }
    public function exportOrderReport(Request $request){
        return Excel::download(new ExportOrderReport($request), 'Order-' . Carbon::now()->format('Y-m-d') .'.xlsx');
    }
    public function exportCustomerReport(Request $request){
        return Excel::download(new ExportCustomerReport($request), 'Customer-' . Carbon::now()->format('Y-m-d') .'.xlsx');
    }
    public function exportVendorQuotationReport(Request $request){
        return Excel::download(new ExportVendorQuotationReport($request), 'Vendor Quotation-' . Carbon::now()->format('Y-m-d') .'.xlsx');
    }
}
