<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Order Payment Details</h5>
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
                                                <td class="col-sm-5"><strong>Order ID</strong></td>
                                                <td>{{$data->order_id}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>User Name</strong></td>
                                                <td>{{$data->user->name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Vendor Name</strong></td>
                                                <td>{{$data->vendor->vendor_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Product Name</strong></td>
                                                <td>{{$data->product->product_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Payment Mode</strong></td>
                                                <td>{{onlinePaymentMode($data->payment_mode)}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Payment Status</strong></td>
                                                <td>{{paymentStatusType($data->payment_status);}}</td>
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
                                                <td><strong>Date Time</strong></td>
                                                <td>{{date('d-m-Y h:i A', strtotime($data->updated_at)) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Order Payment Image</strong></td>
                                                <td><img src="{{ListingImageUrl('order_payment',$data->order_payment_image)}}" width="150px" height="auto"/></td>
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
