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
                                            <td class="col-sm-5"><strong>Notification Title</strong></td>
                                            <td>{{$data->title}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Notification Body</strong></td>
                                            <td>{{$data->body}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>User Type</strong></td>
                                            <td>{{messageUserType($data->user_type)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Trigger</strong></td>
                                            <td>{{messageTrigger($data->trigger)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>{{displayStatus($data->status)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Last Time Sent</strong></td>
                                            <td>{{date('d-m-Y H:i A', strtotime($data->notification_date)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Notification Image</strong></td>
                                            <td><img src="{{ListingImageUrl('notification',$data->notification_image)}}" width="150px" height="auto"/></td>
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