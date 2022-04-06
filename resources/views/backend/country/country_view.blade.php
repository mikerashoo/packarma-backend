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
                                            <td><strong>Country</strong></td>
                                            <td>{{$data->country_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone Code</strong></td>
                                            <td>{{$data->phone_code}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone Number Length</strong></td>
                                            <td>{{$data->phone_length}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Currency Code</strong></td>
                                            <td>{{$data->currency->currency_code}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Country Status</strong></td>
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