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
                                            <td class="col-sm-5"><strong>User Type</strong></td>
                                            <td>{{messageUserType($data->user_type)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Operation</strong></td>
                                            <td>{{$data->operation}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Trigger</strong></td>
                                            <td>{{messageTrigger($data->trigger)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Message</strong></td>
                                            <td>{{$data->message}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>{{displayStatus($data->status)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date Time</strong></td>
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