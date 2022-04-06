<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Vendor Warehouse Details</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <td class="col-sm-5"><strong>Warehouse Name</strong></td>
                                            <td>{{$data->warehouse_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Vendor Name</strong></td>
                                            <td>{{$data->vendor->vendor_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Address</strong></td>
                                            <td>{{$data->address}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pincode</strong></td>
                                            <td>{{$data->pincode}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>City</strong></td>
                                            <td>{{$data->city->city_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>State</strong></td>
                                            <td>{{$data->state->state_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Country</strong></td>
                                            <td>{{$data->country->country_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Warehouse Status</strong></td>
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
