<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Customer Enquiry Details</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a href="#details" role="tab" id="details-tab" class="nav-link d-flex align-items-center active" data-toggle="tab" aria-controls="details" aria-selected="true">
                                        <i class="ft-info mr-1"></i>
                                        <span class="d-none d-sm-block">Enquiry Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#map_to_grade" role="tab" id="page_description-tab" class="nav-link d-flex align-items-center" data-toggle="tab" aria-controls="page_description" aria-selected="false">
                                        <i class="ft-link mr-2"></i>
                                        <span class="d-none d-sm-block">Mapped Grade Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#map_to_vendor" role="tab" id="page_description-tab" class="nav-link d-flex align-items-center" data-toggle="tab" aria-controls="page_description" aria-selected="false">
                                        <i class="ft-link mr-2"></i>
                                        <span class="d-none d-sm-block">Mapped Vendor Details</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade mt-2 show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                                     <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <tr>
                                                        <td><strong>Description</strong></td>
                                                        <td>{{$data->description;}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>User Name</strong></td>
                                                        <td>{{$data['user']->name;}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Enquiry Type</strong></td>
                                                        <td>{{customerEnquiryType($data->enquiry_type); }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Quote Type</strong></td>
                                                        <td>{{customerEnquiryQuoteType($data->quote_type); }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Address</strong></td>
                                                        <td>{{$data->address;}}</td>
                                                    </tr> 
                                                    <tr>
                                                        <td><strong>City</strong></td>
                                                        <td>{{$data['city']->city_name;}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>State</strong></td>
                                                        <td>{{$data['state']->state_name;}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Pincode</strong></td>
                                                        <td>{{$data->pincode;}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Country</strong></td>
                                                        <td>{{$data['country']->country_name;}}</td>
                                                    </tr>                                                                                       
                                                    <tr>
                                                        <td><strong>Enquiry Date Time</strong></td>
                                                        <td>{{date('d-m-Y H:i A', strtotime($data->updated_at)) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade mt-2" id="map_to_grade" role="tabpanel" aria-labelledby="page_description-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    @if (empty($grade->toArray()))
                                                        <tr>
                                                            <td colspan="6" class="text-center">No grade map found</td>
                                                        </tr>
                                                    @else
                                                    @foreach ($grade as $grades)
                                                        <tr>
                                                            <th class="col-sm-3">Grade Name</th>
                                                            <td>{{$grades->grade_name;}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Stock Quantity</th>
                                                            <td>{{$grades->stock_qty;}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Unit Per Kg</th>
                                                            <td>{{$grades->unit;}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Minimumn Order Quantity</th>
                                                            <td>{{$grades->minimum_order_qty;}}</td>
                                                        </tr>
                                                    @endforeach
                                                    @endif 
                                                </table>
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="tab-pane fade mt-2" id="map_to_vendor" role="tabpanel" aria-labelledby="page_description-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <th>Vendor Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Address</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($vendor as $vendors)
                                                        <tr>
                                                            <td>{{ $vendors->vendor_name; }}</td>
                                                            <td>{{ $vendors->vendor_email; }}</td>
                                                            <td>{{ $vendors->phone; }}</td>
                                                            <td>{{ $vendors->vendor_address; }}</td>                                                     </tr>
                                                        @endforeach
                                                        @if (empty($vendor->toArray())) 
                                                         <tr><td colspan="4" class="text-center col">No vendor map found</td></tr>  
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
        </div>
    </div>
</section>
