<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Order Details : {{ $data[0]['order_id']}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a href="#order_details" role="tab" id="details-tab" class="nav-link d-flex align-items-center active" data-toggle="tab" aria-controls="details" aria-selected="true">
                                        <i class="ft-info mr-1"></i>
                                        <span class="d-none d-sm-block">Order Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#product_details" role="tab" id="page_description-tab" class="nav-link d-flex align-items-center" data-toggle="tab" aria-controls="page_description" aria-selected="false">
                                        <i class="ft-link mr-2"></i>
                                        <span class="d-none d-sm-block">Product Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#shipping_details" role="tab" id="page_description-tab" class="nav-link d-flex align-items-center" data-toggle="tab" aria-controls="page_description" aria-selected="false">
                                        <i class="ft-link mr-2"></i>
                                        <span class="d-none d-sm-block">Shipping Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#billing_details" role="tab" id="page_description-tab" class="nav-link d-flex align-items-center" data-toggle="tab" aria-controls="page_description" aria-selected="false">
                                        <i class="ft-link mr-2"></i>
                                        <span class="d-none d-sm-block">Billing Details</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade mt-2 show active" id="order_details" role="tabpanel" aria-labelledby="page_description-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <tr>
                                                        <td class="col-sm-5"><strong>User Name</strong></td>
                                                        <td>{{$data[0]['user']['name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Customer Enquiry ID</strong></td>
                                                        <td>{{$data[0]['customer_enquiry_id']; }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Vendor Quotation ID</strong></td>
                                                        <td>{{$data[0]['vendor_quotation_id']; }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Vendor Name</strong></td>
                                                        <td>{{$data[0]['vendor']['vendor_name']; }}</td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td><strong>vendor Warehouse</strong></td>
                                                        <td>{{$data[0]['vendor_warehouse']['warehouse_name']; }}</td>
                                                    </tr> --}}
                                                    <tr>
                                                        <td><strong>MRP</strong></td>
                                                        <td>{{$data[0]['mrp'];}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Product Quantity</strong></td>
                                                        <td>{{$data[0]['product_quantity'];}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Country</strong></td>
                                                        <td>{{$data[0]['country']['country_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Currency</strong></td>
                                                        <td>{{$data[0]['currency']['currency_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Sub Total</strong></td>
                                                        <td>{{$data[0]['currency']['currency_symbol'].' '.$data[0]['sub_total'];}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>GST Type</strong></td>
                                                        <td>{{gstType($data[0]['gst_type']);}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>GST Percentage</strong></td>
                                                        <td>{{$data[0]['gst_percentage'];}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>GST Amount</strong></td>
                                                        <td>{{$data[0]['currency']['currency_symbol'].' '.$data[0]['gst_amount'];}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Order Amount</strong></td>
                                                        <td>{{$data[0]['currency']['currency_symbol'].' '.$data[0]['grand_total']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Delivery Status</strong></td>
                                                        <td>{{deliveryStatus($data[0]['order_delivery_status']);}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Customer Pending Payment</strong></td>
                                                        <td>{{$data[0]['currency']['currency_symbol'].' '.$data[0]['customer_pending_payment']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Customer Payment Status</strong></td>
                                                        <td>{{customerPaymentStatus($data[0]['customer_payment_status']);}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Vendor Pending Payment</strong></td>
                                                        <td>{{$data[0]['currency']['currency_symbol'].' '.$data[0]['vendor_pending_payment']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Vendor Payment Status</strong></td>
                                                        <td>{{paymentStatus($data[0]['vendor_payment_status']);}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Order Date Time</strong></td>
                                                        <td>{{date('d-m-Y h:i A', strtotime($data[0]['updated_at'])) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade mt-2" id="product_details" role="tabpanel" aria-labelledby="page_description-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <tr>
                                                        <td class="col-sm-5"><strong>Category Name</strong></td>
                                                        <td>{{$data[0]['category']['category_name']; }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Sub Category</strong></td>
                                                        <td>{{$data[0]['sub_category']['sub_category_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Product Name</strong></td>
                                                        <td>{{$data[0]['product']['product_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Shelf Life</strong></td>
                                                        <td>{{$data[0]['shelf_life']}} Days</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Product Weight</strong></td>
                                                        <td>{{$data[0]['product_weight'].' '.$data[0]['measurement_unit']['unit_symbol']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Product Quantity</strong></td>
                                                        <td>{{$data[0]['product_quantity']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Storage Condition</strong></td>
                                                        <td>{{$data[0]['storage_condition']['storage_condition_title']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Packaging Machine Name</strong></td>
                                                        <td>{{$data[0]['packaging_machine']['packaging_machine_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Product Form Name</strong></td>
                                                        <td>{{$data[0]['product_form']['product_form_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Packing Type</strong></td>
                                                        <td>{{$data[0]['packing_type']['packing_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Packaging Treatment</strong></td>
                                                        <td>{{$data[0]['packaging_treatment']['packaging_treatment_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Packaging Solutuon</strong></td>
                                                        <td>{{$data[0]['recommendation_engine']['engine_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Packaging Material</strong></td>
                                                        <td>{{$data[0]['packaging_material']['packaging_material_name']}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade mt-2" id="shipping_details" role="tabpanel" aria-labelledby="page_description-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <tr>
                                                        <td class="col-sm-5"><strong>Address Name</strong></td>
                                                        <td>{{$data[0]['shipping_details']['address_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Address Type</strong></td>
                                                        <td>{{$data[0]['shipping_details']['type']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Mobile No</strong></td>
                                                        <td>{{$data[0]['shipping_details']['mobile_no']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>State</strong></td>
                                                        <td>{{$data[0]['shipping_details']['state_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>City</strong></td>
                                                        <td>{{$data[0]['shipping_details']['city_name'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Pincode</strong></td>
                                                        <td>{{$data[0]['shipping_details']['pincode']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Area</strong></td>
                                                        <td>{{$data[0]['shipping_details']['area']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Flat</strong></td>
                                                        <td>{{$data[0]['shipping_details']['flat']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Landmark</strong></td>
                                                        <td>{{$data[0]['shipping_details']['land_mark']}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade mt-2" id="billing_details" role="tabpanel" aria-labelledby="page_description-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <tr>
                                                        <td class="col-sm-5"><strong>Address Name</strong></td>
                                                        <td>{{$data[0]['billing_details']['address_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Address Type</strong></td>
                                                        <td>{{$data[0]['billing_details']['type']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>GST Number</strong></td>
                                                        <td>{{$data[0]['billing_details']['gstin']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Mobile No</strong></td>
                                                        <td>{{$data[0]['billing_details']['mobile_no']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>State</strong></td>
                                                        <td>{{$data[0]['billing_details']['state_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>City</strong></td>
                                                        <td>{{$data[0]['billing_details']['city_name']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Area</strong></td>
                                                        <td>{{$data[0]['billing_details']['area']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Flat</strong></td>
                                                        <td>{{$data[0]['billing_details']['flat']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Landmark</strong></td>
                                                        <td>{{$data[0]['billing_details']['land_mark']}}</td>
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
        </div>
    </div>
</section>
