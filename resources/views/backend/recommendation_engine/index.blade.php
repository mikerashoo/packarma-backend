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
                                            <h5 class="pt-2">Manage Packaging Solution List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            @if($data['packaging_solution_add'])
                                                <a href="packaging_solution_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Packaging Solution</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Packaging Solution Name</label>
                                            <input class="form-control mb-3" type="text" id="search_recommendation_engine" name="search_recommendation_engine">
                                        </div>
                                        {{-- <div class="col-sm-4">
                                            <label>Structure type</label>
                                            <select class="form-control mb-3 select2" id="search_structure_type" name="search_structure_type" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['user'] as $users)
                                                    <option value="{{$users->id}}">{{$users->name}}{{$user_deleted_status}}</option>                                                
                                                @endforeach
                                            </select><br/>
                                        </div> --}}
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="packaging_solution_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="engine_name" data-orderable="false" data-searchable="false">Packaging Solution Name</th>
                                                    <th id="structure_type" data-orderable="false" data-searchable="false">Structure Type</th>
                                                    <th id="product_name" data-orderable="false" data-searchable="false">Product Name</th>
                                                    @if($data['packaging_solution_view'] || $data['packaging_solution_edit'] || $data['packaging_solution_status'])
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