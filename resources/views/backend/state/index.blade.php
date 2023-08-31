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
                                            <h5 class="pt-2">Manage State List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            @if($data['state_add'])
                                                <a href="state_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add State</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-sm-4">
                                            <label>State Name</label>
                                            <input class="form-control mb-3" type="text" id="search_state_name" name="search_state_name">
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Country Name</label>
                                            <select class="form-control mb-3 select2" id="search_country" name="search_country" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['country'] as $countries)
                                                    <option value="{{$countries->id}}">{{$countries->country_name}}</option>
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="state_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="state_name" data-orderable="false" data-searchable="false">State</th>
                                                    <th id="country_name" data-orderable="false" data-searchable="false">Country</th>
                                                    @if($data['state_status'])
                                                        <th id="status" data-orderable="false" data-searchable="false" width="130px">Status</th>
                                                    @endif
                                                    @if($data['state_edit'] || $data['state_view'])
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