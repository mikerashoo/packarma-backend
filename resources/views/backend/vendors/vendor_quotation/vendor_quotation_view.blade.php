<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Vendor Quotation Details</h5>
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
                                                <td><strong>Enquiry ID</strong></td>
                                                <td>{{getFormatid($data->customer_enquiry_id);}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Quotation ID</strong></td>
                                                <td>{{getFormatid($data->id, 'vendor_quotations');}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Vendor Name</strong></td>
                                                <td>{{$data->vendor->vendor_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Warehouse Name</strong></td>
                                                @if ($data->vendor_warehouse->warehouse_name)
                                                    <td>{{$data->vendor_warehouse->warehouse_name}}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td><strong>Product Name</strong></td>
                                                <td>{{$data->product->product_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Product Quantity</strong></td>
                                                <td>{{$data->product_quantity}} {{ $recommedation_data->min_order_quantity_unit }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Vendor Price</strong></td>
                                                <td>{{ $currency->currency_symbol }} {{$data->vendor_amount}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Vendor Price Per {{ $recommedation_data->min_order_quantity_unit }}</strong></td>
                                                <td>{{ $currency->currency_symbol }} {{$data->vendor_price}}</td>
                                            </tr>
                                            {{-- <tr>
                                                <td><strong>Commission Rate Per {{ $recommedation_data->min_order_quantity_unit }}</strong></td>
                                                <td>{{ $currency->currency_symbol }} {{$data->commission_amt}}</td>
                                            </tr> --}}
                                            <tr>
                                                <td><strong>Delivery in Days</strong></td>
                                                <td>{{$data->delivery_in_days}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Delivery Charges</strong></td>
                                                <td>{{ $currency->currency_symbol }} {{$data->freight_amount}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Enquiry Status</strong></td>
                                                <td>{{vendorEnquiryStatus($data->enquiry_status)}}</td>
                                            </tr>
                                            {{-- <tr>
                                                <td><strong>Quotation Expiry Date Time</strong></td>
                                                <td>{{date('d-m-Y H:i:s', strtotime($data->quotation_expiry_datetime)) }}</td>
                                            </tr> --}}
                                            <tr>
                                                <td><strong>Lead Time (Days)</strong></td>
                                                <td>{{$data->lead_time}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Creation Date Time</strong></td>
                                                <td>{{date('d-m-Y H:i', strtotime($data->created_at)) }}</td>
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
