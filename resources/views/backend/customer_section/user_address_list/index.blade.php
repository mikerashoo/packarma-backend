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
                                                <h5 class="pt-2">Manage User Address List</h5>
                                            </div>
                                            <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                                <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                                    @if (isset($id))
                                                        <a href="add_user_address?id={{ $id }}" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add User Address</a>
                                                    @else
                                                        <a href="add_user_address" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add User Address</a>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-2" id="listing-filter-data" style="<?php echo (isset($id)) ? '' : 'display: none' ?>">
                                            <div class="col-sm-4">
                                                <label>User</label>
                                                <select class="form-control select2" id="search_user" name="search_user" style="width: 100% !important;">
                                                    @if (!isset($id))
                                                        <option value="">Select</option>
                                                    @endif
                                                    @foreach ($user as $users)
                                                        @if (isset($id))
                                                            @if ($users->id == $id)
                                                                <option selected value="{{ $users->id }}">{{ $users->name }}</option>
                                                            @endif
                                                        @else
                                                            <option value="{{ $users->id }}">{{ $users->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select><br><br>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>City</label>
                                                <select class="form-control mb-3 select2" id="search_city" name="search_city" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    @foreach ($city as $cities)
                                                        <option value="{{ $cities->id }}">{{ $cities->city_name }}</option>
                                                    @endforeach
                                                </select><br />
                                            </div>
                                            <div class="col-md-4">
                                                <label>&nbsp;</label><br />
                                                <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="user_address_data">
                                                <thead>
                                                    <tr>
                                                        <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                        <th id="name" data-orderable="false" data-searchable="false">User</th>
                                                        <th id="state" data-orderable="false" data-searchable="false">State</th>
                                                        <th id="city" data-orderable="false" data-searchable="false">City</th>
                                                        <th id="pincode" data-orderable="false" data-searchable="false">Pincode</th>
                                                        @if ($user_address_view || $user_address_edit || $user_address_status)
                                                            <th id="action" data-orderable="false" data-searchable="false"width="130px">Action</th>
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
