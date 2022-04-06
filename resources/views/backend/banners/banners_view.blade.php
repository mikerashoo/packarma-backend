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
                                            <td>{{$data->title}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Banners Type</strong></td>
                                            <td>{{$data->type}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Banners Status</strong></td>
                                            <td>{{displayStatus($data->status)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date Time</strong></td>
                                            <td>{{date('d-m-Y H:i A', strtotime($data->updated_at)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Banners Image</strong></td>
                                            <td><img src="{{ListingImageUrl('banner',$data->banner_image)}}" width="150px" height="auto"/></td>
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
