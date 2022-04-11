<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Vendor Details</h5>
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
                                                <td>{{$data->vendor_name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Vendor Email</strong></td>
                                                <td>{{$data->vendor_email}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Vendor Phone</strong></td>
                                                <td>{{'+'.$data->phone_country->phone_code.' '.$data->phone}}</td>
                                            </tr>
                                            @if ($data->whatsapp_no)
                                                <tr>
                                                    <td><strong>Vendor Phone</strong></td>
                                                    <td>{{'+'.$data->whatsapp_country->phone_code.' '.$data->whatsapp_no}}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td><strong>Address</strong></td>
                                                <td>{{$data->vendor_address}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pincode</strong></td>
                                                <td>{{$data->pincode}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Vendor Status</strong></td>
                                                <td>{{displayStatus($data->status)}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Date Time</strong></td>
                                                <td>{{date('d-m-Y H:i A', strtotime($data->updated_at)) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <h5 class="mb-2 text-bold-500"><i class="ft-link mr-2"></i>Vendor Material Mapping View Details</h5>
                                <div class="col-12 col-xl-12 users-module">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered mb-2">
                                            <thead>
                                                <tr>
                                                    <th>Packaging Material Name</th>
                                                    <th>Recommendation Engine Name</th>
                                                    <th>Product Name</th>
                                                    <th>Commission Rate Per Kg</th>
                                                    <th>Commission Rate Per Kg</th>
                                                    <th>Vendor Price</th>
                                                    <th>Date Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($vendor_material_mapping as $value)
                                                    <tr>
                                                        <td>{{ $value->packaging_material->packaging_material_name }}</td>
                                                        <td>{{ $value->recommendation_engine->engine_name }}</td>
                                                        <td>{{ $value->product->product_name }}</td>
                                                        <td>{{ $value->min_amt_profit }}</td>
                                                        <td>{{ $value->min_stock_qty }}</td>
                                                        <td>{{ $value->vendor_price }}</td>
                                                        <td>{{ date('d-m-Y H:i A', strtotime($value->updated_at)) }}</td>
                                                    </tr>
                                                @endforeach
                                                @if (empty($vendor_material_mapping->toArray()))
                                                    <tr>
                                                        <td colspan="7" class="text-center">No Material Map Found</td>
                                                    </tr>
                                                @endif
                                            </tbody>
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