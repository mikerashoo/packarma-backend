<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Order Details</h5>
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
                                                <td><strong>Vendor Name</strong></td>
                                                <td>{{$data->vendor->vendor_name; }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Grade Name</strong></td>
                                                <td>{{$data->grade->grade_name; }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Sub Total</strong></td>
                                                <td>{{$data->sub_total}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Grand Total</strong></td>
                                                <td>{{$data->grand_total}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Order Details</strong></td>
                                                <td>{{$data->order_details}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pending Payment</strong></td>
                                                <td>{{$data->pending_payment}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Delivery Status</strong></td>
                                                <td>{{deliveryStatus($data->order_delivery_status);}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Payment Status</strong></td>
                                                <td>{{paymentStatus($data->payment_status);}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Order Date Time</strong></td>
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
    </div>
</section>
