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
                                            <h5 class="pt-2">Manage Product List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            @if($data['product_add'])
                                                <a href="product_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Product</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Category</label>
                                            <select class="form-control mb-3 select2" id="search_category" name="search_category" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['category'] as $categories)
                                                    <option value="{{$categories->id}}">{{$categories->category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Sub Category</label>
                                            <select class="form-control mb-3 select2" id="search_sub_category" name="search_sub_category" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['sub_category'] as $value)
                                                    <option value="{{$value->id}}">{{$value->sub_category_name}}</option>
                                                @endforeach
                                            </select><br/><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Product Name</label>
                                            <input class="form-control mb-3" type="text" id="search_product_name" name="search_product_name">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Product Form Name</label>
                                            <select class="form-control mb-3 select2" id="search_product_form" name="search_product_form" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['product_form'] as $form)
                                                    <option value="{{$form->id}}">{{$form->product_form_name}}</option>
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Packaging Treatment Name</label>
                                            <select class="form-control mb-3 select2" id="search_packaging_treatment" name="search_packaging_treatment" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['packaging_treatment'] as $treatments)
                                                    <option value="{{$treatments->id}}">{{$treatments->packaging_treatment_name}}</option>
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="product_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="product_name" data-orderable="false" data-searchable="false">Product Name</th>
                                                    <th id="category_name" data-orderable="false" data-searchable="false">Category Name</th>
                                                    <th id="sub_category_name" data-orderable="false" data-searchable="false">Sub Category Name</th>
                                                    <th id="product_form" data-orderable="false" data-searchable="false">Product Form</th>
                                                    <th id="product_image_url" data-orderable="false" data-searchable="false" alt="Image">Product Image</th>
                                                    @if($data['product_status'] || $data['product_edit'] ||$data['product_view'])
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