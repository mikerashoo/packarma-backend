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
                                            <td class="col-sm-5"><strong>User Name</strong></td>
                                            <td>{{$data->user->name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Product Name</strong></td>
                                            <td>{{$data->product->product_name; }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Title</strong></td>
                                            <td>{{$data->title; }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Review</strong></td>
                                            <td>{{$data->review }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Rating</strong></td>
                                            <td>{{$data->rating}}</td>
                                        </tr>
                                        <?php /*
                                        <tr>
                                            <td><strong>Approval Status</strong></td>
                                            <td>{{approvalStatusArray($data->approval_status)}}</td>
                                        </tr>
                                        */ ?>
                                        <tr>
                                            <td><strong>Review Date Time</strong></td>
                                            <td>{{date('d-m-Y H:i A', strtotime($data->updated_at)) }}</td>
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
