<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View User Subscription Payment Details</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tr>
                                                <td class="col-sm-5"><strong>User Name</strong></td>
                                                <td>{{$data->user->name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Subscription Type</strong></td>
                                                <td>{{subscriptionType($data->subscription_type); }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Subscription Start Date</strong></td>
                                                @if (empty($data->user->subscription_start))
                                                    <td>-</td>  
                                                @else
                                                    <td>{{date('d-m-Y', strtotime($data->user->subscription_start)) }}</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td><strong>Subscription End Date</strong></td>
                                                @if (empty($data->user->subscription_end))
                                                    <td>-</td>  
                                                @else
                                                    <td>{{date('d-m-Y', strtotime($data->user->subscription_end)) }}</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td><strong>Payment Mode</strong></td>
                                                <td>{{onlinePaymentMode($data->payment_mode); }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Payment Status</strong></td>
                                                @if ($data->payment_status == 'pending')
                                                    <td>Failed</td>
                                                @else
                                                    <td>{{subscriptionPaymentStatus($data->payment_status);}}</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td><strong>Payment Gateway ID</strong></td>
                                                <td>{{$data->gateway_id}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Payment Gateway Key</strong></td>
                                                @if ($data->gateway_key)
                                                    <td>{{$data->gateway_key}}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td><strong>Date Time</strong></td>
                                                <td>{{date('d-m-Y h:i A', strtotime($data->updated_at)) }}</td>
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
    </div>
</section>
