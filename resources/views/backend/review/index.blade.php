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
                                            <h5 class="pt-2">Manage Reviews</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-sm-4">
                                            <label>User</label>
                                            <select class="form-control select2" id="search_user" name="search_user" style="width: 100% !important;">
                                                @if (!isset($id))
                                                    <option value="">Select</option>
                                                @endif
                                                @foreach ($data['user'] as $users)
                                                    @php
                                                        $isUserDeleted = isRecordDeleted($users->deleted_at);
                                                        $isUserDeleted ? $deleted_status = ' - (Deleted)' : $deleted_status = '';
                                                    @endphp
                                                    @if (isset($id))
                                                        @if ($users->id == $id)
                                                            <option selected value="{{ $users->id }}">{{ $users->name }} {{$deleted_status}}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $users->id }}">{{ $users->name }} {{$deleted_status}}</option>
                                                    @endif
                                                @endforeach
                                            </select><br><br>
                                        </div>                                                                   
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>                                    
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable nowrapClass" id="dataTable" width="100%" cellspacing="0" data-url="review_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="user_id" data-orderable="false" data-searchable="false">User Name</th>
                                                    {{-- <th id="title" data-orderable="false" data-searchable="false">Title</th> --}}
                                                    <th id="product_name" data-orderable="false" data-searchable="false">Product Name</th>
                                                    <th id="rating" data-orderable="false" data-searchable="false">Rating</th>
                                                    <th id="status" data-orderable="false" data-searchable="false">Status</th>
                                                    @if($data['review_status'] || $data['review_edit'] || $data['review_view'])
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
