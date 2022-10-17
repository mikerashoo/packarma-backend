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
                                            <h5 class="pt-2">Manage Sub Category List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            @if($data['sub_category_add'])
                                                <a href="sub_category_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Sub Category</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Sub Category</label>
                                            <input class="form-control mb-3" type="text" id="search_sub_category_name" name="search_sub_category_name">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Category Name</label>
                                            <select class="form-control mb-3 select2" id="search_category_id" name="search_category_id" style="width: 100% !important;">
                                                <option value="" selected>Select</option>
                                                @foreach($data['category'] as $category)
                                                    <option value="{{$category->id}}">{{$category->category_name}}</option>
                                            @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="sub_category_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="sub_category_name" data-orderable="false" data-searchable="false">Sub Category Name</th>
                                                    <th id="category_name" data-orderable="false" data-searchable="false">Category Name</th>
                                                    <th id="sub_category_image_url" data-orderable="false" data-searchable="false">Sub Category Image</th>
                                                    @if($data['sub_category_status'] || $data['sub_category_edit'] ||$data['sub_category_view'])
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