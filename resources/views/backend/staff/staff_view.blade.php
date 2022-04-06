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
                                            <td><strong>Staff Name</strong></td>
                                            <td>{{$data->admin_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong></td>
                                            <td>{{$data->email}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Country</strong></td>
                                            <td>{{$data->country->country_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone</strong></td>
                                            <td><span>+{{ $data['country']->phone_code }}</span><span> {{ $data->phone }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Staff Status</strong></td>
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