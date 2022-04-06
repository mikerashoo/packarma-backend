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
                                            <td class=col-sm-6><strong>Currency Name</strong></td>
                                            <td>{{$data->currency_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Currency Symbol</strong></td>
                                            <td>{{$data->currency_symbol}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Currency Code</strong></td>
                                            <td>{{$data->currency_code}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Exchange Rate</strong></td>
                                            <td>{{$data->exchange_rate}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Currency Status</strong></td>
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