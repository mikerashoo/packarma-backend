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
                                                <h5 class="pt-2">Manage Solution Banners List</h5>
                                            </div>
                                            <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                                <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2"
                                                    id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                                @if ($data['add_solution_banner'])
                                                    <a href="solution_banner_add"
                                                        class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i
                                                            class="fa fa-plus"></i> Add Solution Banners</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                            <div class="col-md-4">
                                                <label>Banner Title</label>
                                                <input class="form-control mb-3" type="text" id="search_banner_title"
                                                    name="search_banner_title">
                                            </div>
                                            <div class="col-md-4">
                                                <label>&nbsp;</label><br />
                                                <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data"
                                                    type="reset" value="Clear Search">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped datatable" id="dataTable"
                                                width="100%" cellspacing="0" data-url="solution_banners_data">
                                                <thead>
                                                    <tr>
                                                        <th class="sorting_disabled" id="id" data-orderable="false"
                                                            data-searchable="false">Id</th>
                                                        <th id="title" data-orderable="false" data-searchable="false">
                                                            Banner Title</th>


                                                        <th id="view_count" data-orderable="false" data-searchable="false"
                                                            width="130px">Views</th>


                                                        <th id="click_count" data-orderable="false" data-searchable="false"
                                                            width="130px">Clicks</th>

                                                        {{-- <th id="start_date_time" data-orderable="false"
                                                            data-searchable="false">
                                                            Start Date Time</th>
                                                        <th id="end_date_time" data-orderable="false"
                                                            data-searchable="false">
                                                            End Date Time</th> --}}

                                                        <th id="description" data-orderable="false" data-searchable="false">
                                                            Banner Description</th>
                                                        <th id="banner_image_url" data-orderable="false"
                                                            data-searchable="false" alt="Image">BANNERS IMAGES </th>
                                                        @if ($data['solution_banner_status'] || $data['solution_banner_edit'] || $data['solution_banner_view'])
                                                            <th id="action" data-orderable="false"
                                                                data-searchable="false" width="130px">Action</th>
                                                        @endif
                                                    </tr>
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
