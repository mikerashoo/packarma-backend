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
                                            <h5 class="pt-2">Manage Vendor Material Mapping List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            @if (isset($id))
                                                <a href="vendor_material_map_add?id={{ $id }}" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Vendor Material</a>
                                            @else
                                                <a href="vendor_material_map_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Vendor Material</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="<?php echo (isset($id)) ? '' : 'display: none' ?>">
                                        <div class="col-sm-4">
                                            <label>Vendor Name</label>
                                            <select class="form-control select2" id="search_vendor" name="search_vendor" style="width: 100% !important;">
                                                @if (!isset($id))
                                                    <option value="">Select</option>
                                                @endif
                                                @foreach ($vendor as $vendors)

                                                @php
                                                    $isVendorDeleted = isRecordDeleted($vendors->deleted_at);
                                                    $isVendorDeleted ? $deleted_status = ' - (Deleted)' : $deleted_status = '';
                                                @endphp
                                                    @if (isset($id))
                                                        @if ($vendors->id == $id)
                                                            <option selected value="{{ $vendors->id }}">{{ $vendors->vendor_name }}{{$deleted_status}}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $vendors->id }}">{{ $vendors->vendor_name }}{{$deleted_status}}</option>
                                                    @endif
                                                @endforeach
                                            </select><br><br>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Material Name</label>
                                            <select class="form-control mb-3 select2" id="search_material" name="search_material" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($packaging_material as $materials)
                                                    <option value="{{$materials->id}}">{{$materials->packaging_material_name}}</option>
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="vendor_material_map_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="vendor_name" data-orderable="false" data-searchable="false">Vendor Name</th>
                                                    <th id="material_name" data-orderable="false" data-searchable="false">Packaging Material Name</th>
                                                    <th id="min_amt_profit" data-orderable="false" data-searchable="false">Commission Rate Per Kg</th>
                                                    <th id="vendor_price" data-orderable="false" data-searchable="false">Vendor Price</th>
                                                    @if($vendor_material_map_status)
                                                        <th id="vendor_material_map_status" data-orderable="false" data-searchable="false">Status</th>
                                                    @endif
                                                    @if($vendor_material_map_edit || $vendor_material_map_view) 
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