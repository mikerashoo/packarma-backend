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
                                            <h5 class="pt-2">Manage Customer Enquiry List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            @if($data['customer_enquiry_add'])
                                                <a href="customer_enquiry_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Customer Enquiry</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Description</label>
                                            <input class="form-control mb-3" type="text" id="search_description" name="search_description">
                                        </div>
                                        <div class="col-sm-4">
                                            <label>User Name</label>
                                            <select class="form-control mb-3 select2" id="search_user_name" name="search_user_name" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['user'] as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>                                                
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Enquiry Type</label>
                                            <select class="form-control mb-3 select2" id="search_enquiry_type" name="search_enquiry_type" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['enquiryType'] as $key => $val)
                                                    <option value="{{$key}}">{{$val}}</option>                                                
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Quote Type</label>
                                            <select class="form-control mb-3 select2" id="search_quote_type" name="search_quote_type" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['quoteType'] as $key => $val)
                                                    <option value="{{$key}}">{{$val}}</option>                                                
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="customer_enquiry_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="description" data-orderable="false" data-searchable="false">Description</th>
                                                    <th id="name" data-orderable="false" data-searchable="false">User Name</th>
                                                    <th id="order_id" data-orderable="false" data-searchable="false">Order ID</th>
                                                    <th id="enquiry_type" data-orderable="false" data-searchable="false">Enquiry Type</th>
                                                    <th id="quote_type" data-orderable="false" data-searchable="false">Quote Type</th>
                                                    <th id="updated_at" data-orderable="false" data-searchable="false">Date Time</th>                                                    
                                                    @if($data['customer_enquiry_view'] || $data['customer_enquiry_edit'] || $data['customer_enquiry_map_to_vendor'])
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