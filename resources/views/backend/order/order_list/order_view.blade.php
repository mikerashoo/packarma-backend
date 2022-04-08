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
                                                <td><strong>Category Name</strong></td>
                                                <td>{{$data->category->category_name; }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Sub Category</strong></td>
                                                <td>{{$data->sub_category->sub_category_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Product Name</strong></td>
                                                <td>{{$data->product->product_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Shelf Life</strong></td>
                                                <td>{{$data->shelf_life}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Product Weight</strong></td>
                                                <td>{{$data->product_weight}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Storage Condition</strong></td>
                                                <td>{{$data->storage_condition->storage_condition_title}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Packaging Machine Name</strong></td>
                                                <td>{{$data->packaging_machine->packaging_machine_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Product Form Name</strong></td>
                                                <td>{{$data->product_form->product_form_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Packing Type</strong></td>
                                                <td>{{$data->packing_type->packing_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Packaging Treatment</strong></td>
                                                <td>{{$data->packaging_treatment->packaging_treatment_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Country</strong></td>
                                                <td>{{$data->country->country_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Currency</strong></td>
                                                <td>{{$data->currency->currency_name}}</td>
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
                                                <td><strong>Delivery Status</strong></td>
                                                <td>{{deliveryStatus($data->order_delivery_status);}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Customer Pending Payment</strong></td>
                                                <td>{{$data->customer_pending_payment}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Customer Payment Status</strong></td>
                                                <td>{{paymentStatus($data->customer_payment_status);}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Vendor Pending Payment</strong></td>
                                                <td>{{$data->vendor_pending_payment}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Vendor Payment Status</strong></td>
                                                <td>{{paymentStatus($data->vendor_payment_status);}}</td>
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
