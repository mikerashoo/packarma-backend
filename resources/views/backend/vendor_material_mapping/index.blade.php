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
                                                <a href="vendorMaterialMapAdd?id={{ $id }}" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Vendor Material</a>
                                            @else
                                                <a href="vendorMaterialMapAdd" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Vendor Material</a>
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
                                                    @if (isset($id))
                                                        @if ($vendors->id == $id)
                                                            <option selected value="{{ $vendors->id }}">{{ $vendors->vendor_name }}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $vendors->id }}">{{ $vendors->vendor_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select><br><br>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Material Name</label>
                                            <select class="form-control mb-3 select2" id="search_material" name="search_material" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($packagingMaterial as $materials)
                                                    <option value="{{$materials->id}}">{{$materials->material_name}}</option>
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="vendorGradeMapData">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="vendor_name" data-orderable="false" data-searchable="false">Vendor Name</th>
                                                    <th id="material_name" data-orderable="false" data-searchable="false">Material Name</th>
                                                    <th id="min_amt_profit" data-orderable="false" data-searchable="false">Commission Rate Per Kg</th>
                                                    <th id="min_stock_qty" data-orderable="false" data-searchable="false">Commission Rate Per Quantity</th>
                                                    @if($vendor_grade_map_status)
                                                        <th id="vendor_grade_map_status" data-orderable="false" data-searchable="false">Status</th>
                                                    @endif
                                                    @if($vendor_grade_edit || $vendor_grade_view) 
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