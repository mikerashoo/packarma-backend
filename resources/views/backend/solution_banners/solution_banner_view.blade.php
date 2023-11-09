<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <td><strong>Banners Title</strong></td>
                                            <td>{{ $data->title }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Banners Description</strong></td>
                                            <td>{{ $data->description }}</td>
                                        </tr>

                                        <tr>
                                            <td><strong>Banner Link</strong></td>
                                            <td>{{ $data->link }}</td>
                                        </tr>

                                        <tr>
                                            <td><strong>Banner App Link</strong></td>
                                            <td>{{ $data->page ? $data->page->pageName : '-' }}</td>
                                        </tr>

                                        <tr>
                                            <td><strong>Banner Products</strong></td>
                                            <td>
                                                @foreach ($data->products as $product)
                                                    <p>{{ $product->product_name }}</p>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><strong>Banners Status</strong></td>
                                            <td>{{ displayStatus($data->status) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Start Date Time</strong></td>
                                            <td>{{ date('d-m-Y h:i A', strtotime($data->start_date_time)) }}</td>
                                        </tr>

                                        <tr>
                                            <td><strong>End Date Time</strong></td>
                                            <td>{{ date('d-m-Y h:i A', strtotime($data->end_date_time)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date Time</strong></td>
                                            <td>{{ date('d-m-Y h:i A', strtotime($data->updated_at)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Banners Image</strong></td>
                                            <td><img src="{{ ListingImageUrl('banner', $data->banner_image) }}"
                                                    width="150px" height="auto" /></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
