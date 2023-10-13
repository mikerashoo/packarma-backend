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
                                            <h5 class="pt-2">Manage Vendor Warehouse List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            @if($data['vendor_warehouse_add'])
                                                <a href="vendor_warehouse_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Vendor Warehouse</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Warehouse Name</label>
                                            <input class="form-control mb-3" type="text" id="search_warehouse_name" name="search_warehouse_name">
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Vendor Name</label>
                                            <select class="form-control mb-3 select2" id="search_vendor" name="search_vendor" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['vendor'] as $vendors)
                                                    <option value="{{$vendors->id}}">{{$vendors->vendor_name}}</option>
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>City Name</label>
                                            <select class="form-control mb-3 select2" id="search_city" name="search_city" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['city'] as $cities)
                                                    <option value="{{$cities->id}}">{{$cities->city_name}}</option>
                                                @endforeach
                                            </select>
                                        </div><br>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="vendor_warehouse_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="warehouse_name" data-orderable="false" data-searchable="false">Warehouse Name</th>
                                                    <th id="vendor_name" data-orderable="false" data-searchable="false">Vendor Name</th>
                                                    <th id="state" data-orderable="false" data-searchable="false">State</th>
                                                    <th id="city" data-orderable="false" data-searchable="false">City</th>
                                                    <th id="address" data-orderable="false" data-searchable="false">Address</th>
                                                    @if($data['vendor_warehouse_view'] || $data['vendor_warehouse_edit'] || $data['vendor_warehouse_status'])
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