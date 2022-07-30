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
                                            <td><strong>City Name</strong></td>
                                            <td>{{$data->city_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>State Name</strong></td>
                                            <td>{{$data->state->state_name}}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td><strong>Country Name</strong></td>
                                            <td>{{$data->country->country_name}}</td>
                                        </tr> --}}
                                        <tr>
                                            <td><strong>City Status</strong></td>
                                            <td>{{displayStatus($data->status)}}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td><strong>Date Time</strong></td>
                                            <td>{{date('d-m-Y h:i A', strtotime($data->updated_at)) }}</td>
                                        </tr> --}}
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