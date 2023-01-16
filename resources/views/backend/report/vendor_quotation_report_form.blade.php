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
												<h5 class="pt-2">Vendor Quotation Report</h5>
											</div>
										
										</div>
									</div>
									<!-- <hr class="mb-0"> -->
									<div class="card-body">
										<form id="generateVendorQuotationReportForm" method="post" action="generate_vendor_quotation_report">
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
													<label>Vendor</label>
													<select class="select2" type="text" id="vendor_quotation_vendor" name="vendor_quotation_vendor[]" style="width: 100% !important;" multiple><br/>
													<option value='All'>All</option>	
														@foreach($vendors as $vendor)
															<option value='{{$vendor->id}}'>{{$vendor->vendor_name}}</option>														
														@endforeach
													</select><br/>
												</div>
												<div class="col-sm-6">
													<label>Packaging Mterial</label>
													<select class="select2" id="vendor_quotation_packaging_material" name="vendor_quotation_packaging_material[]" style="width: 100% !important;" multiple>
														<option value='All'>All</option>	
														@foreach($packaging_materials as $packaging_material)
															<option value='{{$packaging_material->id}}'>{{$packaging_material->packaging_material_name}}</option>	
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

