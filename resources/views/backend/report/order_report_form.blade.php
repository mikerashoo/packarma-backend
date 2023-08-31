@extends('backend.layouts.app')
@section('content')
    <div class="main-content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <section class="users-list-wrapper">
               <div class="users-list-table">
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-content">
									<div class="card-header">
										<div class="row">
											<div class="col-12 col-sm-7">
												<h5 class="pt-2">Order Data Report</h5>
											</div>
										
										</div>
									</div>
									<!-- <hr class="mb-0"> -->
									<div class="card-body">
										<form id="generateOrderReportForm" method="post" action="generate_order_report">
											<h4 class="form-section"><i class="ft-info"></i> Filter</h4>
											@csrf
											<div class="row">
												<div class="col-sm-6">
															
													<label>Enquiry Date Range<span class="text-danger">*</span></label>
													<input class="form-control required" type="text" id="daterange" name="daterange" readonly><br/>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<label>Customer</label>
													<select class="select2" type="text" id="order_customer" name="order_customer[]" style="width: 100% !important;" multiple><br/>
													<option value='All'>All</option>	
														@foreach($customers as $customer)
															<option value='{{$customer->id}}'>{{$customer->name}}</option>														
														@endforeach
													</select><br/>
												</div>
												<div class="col-sm-6">
													<label>Vendor</label>
													<select class="select2" type="text" id="order_vendor" name="order_vendor[]" style="width: 100% !important;" multiple><br/>
													<option value='All'>All</option>	
														@foreach($vendors as $vendor)
															<option value='{{$vendor->id}}'>{{$vendor->vendor_name}}</option>														
														@endforeach
													</select><br/><br/>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<label>Product</label>
													<select class="select2" id="order_product" name="order_product[]" style="width: 100% !important;" multiple>
														<option value='All'>All</option>	
														@foreach($products as $product)
															<option value='{{$product->id}}'>{{$product->product_name}} - {{$product->sub_category->sub_category_name}} - {{$product->category->category_name}}</option>	
														@endforeach
													</select><br/>
												</div>
												<div class="col-sm-6">
													<label>Recommendation Engine</label>
													<select class="select2" id="order_recommendation_engine" name="order_recommendation_engine[]" style="width: 100% !important;" multiple>
														<option value='All'>All</option>	
														@foreach($recommendation_engines as $recommendation_engine)
															<option value='{{$recommendation_engine->id}}'>{{$recommendation_engine->engine_name}} - {{$recommendation_engine->structure_type}}</option>	
														@endforeach
													</select><br/>
												</div>
												<div class="col-sm-12">
													<br/>
													@php
                                                        $status = session('status');
                                                    @endphp
                                                    @if($status)
                                                    	<div class='badge bg-light-danger mb-1 mr-2 errors'>
                                                        	<strong>ERROR : </strong>{{ $status }}
                                                        </div>
                                                    @endif
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-sm-12">
													<div class="pull-right">
														<button type="submit" class="btn btn-success export">Export</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
    			</div>
            </section>
        </div>
    </div>
<script>
$(function() {
  $('#daterange').daterangepicker({
    startDate: 	moment(),
    endDate: 	moment(),
	// minDate:	moment(),
	// maxDate:	moment().add(365, 'days'),
    locale: {
      format: 'DD/MM/YYYY'
    }
  });
});

$(document).on('click', '.export', function (event) {
	$('.errors').text('');
    
});
$('.select2').select2();

</script>
@endsection

