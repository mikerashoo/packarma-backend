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
                                            <h5 class="pt-2">Manage Vendor Payment List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            {{-- @if($data['vendor_payment_add'])
                                                <a href="vendor_payment_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Vendor Payment</a>
                                            @endif --}}
                                            @if (isset($id))
                                                <a href="vendor_payment_add?id={{ $id }}" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Vendor Payment</a>
                                            @else
                                                <a href="vendor_payment_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Vendor Payment</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="<?php echo (isset($id)) ? '' : 'display: none' ?>">
                                        <div class="col-md-4">
                                            <label>Order ID</label>
                                            @if (isset($id))
                                                <input class="form-control mb-3" type="text" id="search_order_id" value="{{ $id }}" name="search_order_id">
                                            @else
                                                <input class="form-control mb-3" type="text" id="search_order_id" name="search_order_id">
                                            @endif
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Vendor Name</label>
                                            <select class="form-control mb-3 select2" id="search_vendor_name" name="search_vendor_name" style="width: 100% !important;">
                                                @if (!isset($id))
                                                    <option value="">Select</option>
                                                @endif
                                                @foreach ($vendor as $vendors)
                                                    @if (isset($id))
                                                        @if ($vendors->id == $id )
                                                            <option value="{{ $vendors->id }}" selected>{{ $vendors->vendor_name }}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $vendors->id }}">{{ $vendors->vendor_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="vendor_payment_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="vendor_name" data-orderable="false" data-searchable="false">Vendor Name</th>
                                                    <th id="order_id" data-orderable="false" data-searchable="false">Order ID</th>
                                                    <th id="payment_mode" data-orderable="false" data-searchable="false">Payment Mode</th>
                                                    <th id="payment_status" data-orderable="false" data-searchable="false">Payment Status</th>
                                                    <th id="transaction_date" data-orderable="false" data-searchable="false">Transaction Date</th>
                                                    <th id="updated_at" data-orderable="false" data-searchable="false">Date Time</th>                                                    
                                                    @if($vendor_payment_view)
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