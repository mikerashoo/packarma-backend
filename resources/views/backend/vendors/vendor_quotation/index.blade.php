@extends('backend.layouts.app')
@section('content')
<div class="main-content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <section class="users-list-wrapper">
        	<div class="users-list-table">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12 col-sm-7">
                                            <h5 class="pt-2">Manage Vendor Quotation List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Enquiry ID</label>
                                            <input class="form-control mb-3" type="text" id="search_enquiry_id" name="search_enquiry_id">
                                        </div>
                                        <div class="col-sm-4">
                                            <label>User Name</label>
                                            <select class="form-control mb-3 select2" id="search_user_name" name="search_user_name" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['user'] as $users)
                                                    <option value="{{$users->id}}">{{$users->name}}</option>                                                
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Vendor Name</label>
                                            <select class="form-control mb-3 select2" id="search_vendor_name" name="search_vendor_name" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['vendor'] as $vendors)
                                                    <option value="{{$vendors->id}}">{{$vendors->vendor_name}}</option>                                                
                                                @endforeach
                                            </select><br>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="vendor_quotation_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="user_name" data-orderable="false" data-searchable="false">User Name</th>
                                                    <th id="customer_enquiry_id" data-orderable="false" data-searchable="false">Enquiry ID</th> 
                                                    <th id="vendor_name" data-orderable="false" data-searchable="false">Vendor Name</th>
                                                    <th id="product_name" data-orderable="false" data-searchable="false">Product Name</th>
                                                    {{-- <th id="vendor_warehouse" data-orderable="false" data-searchable="false">Vendor Warehouse</th> --}}
                                                    <th id="enquiry_status" data-orderable="false" data-searchable="false">Enquiry Status</th>
                                                    <th id="quotation_validity" data-orderable="false" data-searchable="false">Quotation Validity</th>                                                    
                                                    @if($data['vendor_quotation_view'])
                                                        <th id="action" data-orderable="false" data-searchable="false" width="130px">Action</th>
                                                    @endif
				                                </tr>
				                            </thead>
				                        </table>
                                    </div>
                            	</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection