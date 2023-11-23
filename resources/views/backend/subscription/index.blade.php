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
                                            <h5 class="pt-2">Manage Subscription</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-sm-4">
                                            <label>Subscription Type</label>
                                            <select class="form-control mb-3 select2" id="search_type" name="search_type" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['type'] as $key => $val)
                                                    <option value="{{$key}}">{{$val}}</option>
                                                @endforeach
                                            </select><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="subscription_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="type" data-orderable="false" data-searchable="false">Type</th>
                                                    <th id="amount" data-orderable="false" data-searchable="false">Amount</th>
                                                    <th id="credit_amount" data-orderable="false" data-searchable="false">Credit Amount</th>
                                                    <th id="updated_at" data-orderable="false" data-searchable="false">Date Time</th>
                                                    @if($data['subscription_edit'])
                                                        <th id="status" data-orderable="false" data-searchable="false" width="130px">Action</th>
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
