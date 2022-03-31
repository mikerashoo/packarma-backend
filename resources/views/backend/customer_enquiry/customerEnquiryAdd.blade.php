<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Customer Enquiry</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>                        
                    	<div class="card-body">
                    		<form id="addCustomerEnquiryForm" method="post" action="saveCustomerEnquiry">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6 mb-3">
                        				<label>User Name<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2" id="user" value="" name="user" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($user as $users)
                                                <option value="{{ $users->id }}">{{ $users->name }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Country Name<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2" id="country" value="" name="country" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($country as $countries)
                                                    <option value="{{ $countries->id }}">{{ $countries->country_name }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>State Name<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2" id="state" value="" name="state" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($state as $states)
                                                    <option value="{{ $states->id }}">{{ $states->state_name }}</option>
                                            @endforeach
                                        </select><br />
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>City Name<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2" id="city" value="" name="city" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($city as $cities)
                                                    <option value="{{ $cities->id }}">{{ $cities->city_name }}</option>
                                            @endforeach
                                        </select><br />
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Pincode<span style="color:#ff0000">*</span></label>
                        				<input class="form-control" type="text" value=""  id="pincode" name="pincode" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Quantity<span style="color:#ff0000">*</span></label>
                        				<input class="form-control" type="text" value=""  id="quantity" name="quantity" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'/>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Address<span style="color:#ff0000">*</span></label>
                        				<textarea class="form-control" id="address" value="" name="address"></textarea><br><br>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Description<span style="color:#ff0000">*</span></label>
                        				<textarea class="form-control" id="description" value="" name="description"></textarea><br><br>
                        			</div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addCustomerEnquiryForm','post')">Submit</button>
                                            <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
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
<script>
    $('.select2').select2();
</script>