<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Vendor Warehouse</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="addVendorWarehouse" method="post" action="saveVendorWarehouse">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Warehouse Name<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="warehouse_name" name="warehouse_name"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Vendor Name<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="vendor" name="vendor" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($vendor as $vendors)
                                                <option value="{{$vendors->id}}">{{$vendors->vendor_name}}</option>
                                            @endforeach
                                        </select><br/><br/>
                        			</div>
                                    {{-- <div class="col-sm-6">
                                        <label>GST Identification Number</label>
                                        <input class="form-control" type="text" id="gstin" name="gstin"><br />
                                    </div> --}}
                                    <div class="col-sm-6">
                        				<label>Phone Country Code<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="phone_country_code" name="phone_country_code" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($country as $countries)
                                                <option value="{{$countries->id}}">+{{$countries->phone_code}}</option>
                                            @endforeach
                                        </select><br/><br/>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Mobile Number<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="mobile_no" name="mobile_no" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br />
                                    </div>
                                    <div class="col-sm-6">
                        				<label>State<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="state" name="state" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($state as $states)
                                                <option value="{{$states->id}}">{{$states->state_name}}</option>
                                            @endforeach
                                        </select><br/><br/>
                        			</div>
                                    {{-- <div class="col-sm-6">
                        				<label>City<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="city" name="city" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($city as $cities)
                                                <option value="{{$cities->id}}">{{$cities->city_name}}</option>
                                            @endforeach
                                        </select><br/><br/>
                        			</div> --}}
                                    <div class="col-sm-6">
                        				<label>City<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="city" name="city"><br/>
                        			</div>
                                    {{-- <div class="col-sm-6">
                        				<label>Address<span style="color:#ff0000">*</span></label>
                                        <textarea class="form-control required" id="address" name="address" ></textarea><br>
                        			</div> --}}
                                    <div class="col-sm-6">
                        				<label>Pincode<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="pincode" name="pincode" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Area<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="area" name="area"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Flat<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="flat" name="flat"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Landmark<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="landmark" name="landmark"><br/>
                        			</div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addVendorWarehouse','post')">Submit</button>
                                            <a href="{{URL::previous()}}" class="btn btn-danger px-3 py-1"></i>Cancel</a>
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