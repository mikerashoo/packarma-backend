<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Vendor Packaging Material Map Details</h5>
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
                                                <td class="col-sm-5"><strong>Vendor Name</strong></td>
                                                <td>{{$data->vendor->vendor_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Packaging Material Name</strong></td>
                                                <td>{{$data->packaging_material->packaging_material_name; }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Recommendation Engine Name</strong></td>
                                                <td>{{$data->recommendation_engine->engine_name; }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Product Name</strong></td>
                                                <td>{{$data->product->product_name; }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Commission Rate Per Kg</strong></td>
                                                <td>{{$data->min_amt_profit; }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Commission Rate Per Quantity</strong></td>
                                                <td>{{$data->min_stock_qty}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Vendor Price</strong></td>
                                                <td>{{$data->vendor_price}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Vendor Material Map Status</strong></td>
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
    </div>
</section>
