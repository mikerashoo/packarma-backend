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
                                            <td><strong>Vendor Name</strong></td>
                                            <td>{{$data->vendor->vendor_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Order ID</strong></td>
                                            <td>{{$data->order_id}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Mode</strong></td>
                                            <td>{{paymentMode($data->payment_mode)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Status</strong></td>
                                            <td>{{paymentStatusType($data->payment_status);}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Amount</strong></td>
                                            <td>{{$data->amount}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Transaction Date</strong></td>
                                            <td>{{date('d-m-Y', strtotime($data->transaction_date)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Remark</strong></td>
                                            <td>{{$data->remark}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Creation Date Time</strong></td>
                                            <td>{{date('d-m-Y H:i A', strtotime($data->created_at)) }}</td>
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