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
                                            <h5 class="pt-2">Manage Currency List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            @if($data['currency_add'])
                                                <a href="currency_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Currency</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- <hr class="mb-0"> -->
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Currency Name</label>
                                            <input class="form-control mb-3" type="text" id="search_currency_name" name="search_currency_name">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Currency Code</label>
                                            <input class="form-control mb-3" type="text" id="search_currency_code" name="search_currency_code">
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="currency_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="currency_name" data-orderable="false" data-searchable="false">Currency Name</th>
                                                    <th id="currency_symbol" data-orderable="false" data-searchable="false">Currency Symbol</th>
                                                    <th id="currency_code" data-orderable="false" data-searchable="false">Currency Code</th>
                                                    <th id="exchange_rate" data-orderable="false" data-searchable="false">Exchange Rate</th>
                                                    @if($data['currency_edit'] || $data['currency_status'] || $data['currency_view'])
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