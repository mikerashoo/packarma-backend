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
                                            <td><strong>Storage Condition Title</strong></td>
                                            <td>{{$data->storage_condition_title}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Storage Condition Description</strong></td>
                                            <td>{{$data->storage_condition_description}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date Time</strong></td>
                                            <td>{{ date('d-m-Y h:i A', strtotime($data->updated_at)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Storage Condition Status</strong></td>
                                            <td>{{displayStatus($data->status)}}</td>
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
