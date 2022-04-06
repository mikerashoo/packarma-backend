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
                                            <h5 class="pt-2">Manage Staff</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                               @if($data['staff_add'])
                                                <a href="staff_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Staff</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- <hr class="mb-0"> -->
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Name</label>
                                            <input class="form-control mb-3" type="text" id="search_name" name="search_name">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Email</label>
                                            <input class="form-control mb-3" type="email" id="search_email" name="search_email">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Phone</label>
                                            <input class="form-control mb-3" type="text" id="search_phone" name="search_phone">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Role</label>
                                            <select class="form-control mb-3 select2" id="search_role" name="search_role">
                                                <option value="">Select</option>
                                                @foreach($data['roles'] as $roles)
                                                    <option value="{{$roles->id}}">{{$roles->role_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="staff_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="admin_name" data-orderable="false" data-searchable="false">Name</th>
                                                    <th id="email" data-orderable="false" data-searchable="false">Email ID</th>
                                                    <th id="phone" data-orderable="false" data-searchable="false">Phone</th>
                                                    <th id="role" data-orderable="false" data-searchable="false">Role</th>
                                                    @if($data['staff_edit'] || $data['staff_view'] || $data['staff_status'])
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
<script>
    $('.select2').select2();
</script>