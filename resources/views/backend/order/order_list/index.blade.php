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
                                            <h5 class="pt-2">Manage Order List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                        </div>
                                    </div>
                                </div>
                            	 <div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Order ID</label>
                                            <input class="form-control mb-3" type="text" id="search_order_id" name="search_order_id" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'>
                                        </div>
                                        <div class="col-md-4">
                                            <label>User Name</label>
                                            <select class="form-control mb-3 select2" id="search_user_id" name="search_user_id" style="width: 100% !important;">
                                                <option value="" selected>Select</option>
                                                @foreach($data['user'] as $users)
                                                    @php
                                                        $isUserDeleted = isRecordDeleted($users->deleted_at);
                                                        $isUserDeleted ? $deleted_status = ' - (Deleted)' : $deleted_status = '';
                                                    @endphp
                                                    <option value="{{$users->id}}">{{$users->name}} {{ $deleted_status }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Vendor Name</label>
                                            <select class="form-control mb-3 select2" id="search_vendor_id" name="search_vendor_id" style="width: 100% !important;">
                                                <option value="" selected>Select</option>
                                                @foreach($data['vendor'] as $vendors)
                                                    @php
                                                        $isVendorDeleted = isRecordDeleted($vendors->deleted_at);
                                                        $isVendorDeleted ? $deleted_status = ' - (Deleted)' : $deleted_status = '';
                                                    @endphp
                                                    <option value="{{$vendors->id}}">{{$vendors->vendor_name}} {{ $deleted_status }}</option>
                                            @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Packaging Material Name</label>
                                            <select class="form-control mb-3 select2" id="search_packaging_material" name="search_packaging_material" style="width: 100% !important;">
                                                <option value="" selected>Select</option>
                                                @foreach($data['packaging_material'] as $material)
                                                    <option value="{{$material->id}}">{{$material->packaging_material_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Product Name</label>
                                            <select class="form-control mb-3 select2" id="search_product_name" name="search_product_name" style="width: 100% !important;">
                                                <option value="" selected>Select</option>
                                                @foreach($data['product'] as $val)
                                                    <option value="{{$val->id}}">{{$val->product_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Delivery Status</label>
                                            <select class="form-control mb-3 select2" id="search_delivery_status" name="search_delivery_status" style="width: 100% !important;">
                                                <option value="" selected>Select</option>
                                                @foreach($data['deliveryStatus'] as $key => $val)
                                                    <option value="{{$key}}">{{$val}}</option>
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div> 
                            		<div class="table-responsive" style="height:450px;">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="order_data">
				                            <thead class="sticky_header">
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="user_name" data-orderable="false" data-searchable="false">User Name </th>
                                                    <th id="vendor_name" data-orderable="false" data-searchable="false">Vendor Name</th>
                                                    <th id="grand_total" data-orderable="false" data-searchable="false">Order Amount</th>
                                                    <th id="product_quantity" data-orderable="false" data-searchable="false">Product Quantity</th>
                                                    <th id="order_delivery_status" data-orderable="false" data-searchable="false">Delivery Status</th>
                                                    {{-- <th id="payment_status" data-orderable="false" data-searchable="false">Payment Status</th> --}}
                                                    <th id="packaging_material" data-orderable="false" data-searchable="false">Packaging Material Name</th>
                                                    @if($data['order_view'] || $data['order_delivery_update'] || $data['order_payment_update'] || $data['vendor_payment_update'])
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