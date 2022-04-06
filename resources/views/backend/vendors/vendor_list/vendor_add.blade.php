<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Vendor</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="addVendorForm" method="post" action="saveVendor">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Vendor Name<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="vendor_name" name="vendor_name"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Vendor Email<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="email" id="vendor_email" name="vendor_email"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Phone Country Code<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="phone_country_code" name="phone_country_code" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($phone_country as $val)
                                                <option value="{{$val->id}}">+{{$val->phone_code}}</option>
                                            @endforeach
                                        </select><br/>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Phone<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="phone" name="phone" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                                    </div>
                                    <div class="col-sm-6">
                        				<label>Whatsapp Country Code</label>
                        				<select class="select2" id="whatsapp_country_code" name="whatsapp_country_code" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($whatsapp_country as $val)
                                                <option value="{{$val->id}}">+{{$val->phone_code}}</option>
                                            @endforeach
                                        </select><br/>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Whatsapp Number</label>
                                        <input class="form-control" type="text" id="whatsapp_no" name="whatsapp_no" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Vendor Address<span style="color:#ff0000">*</span></label>
                                        <textarea class="form-control required" id="vendor_address" name="vendor_address"></textarea><br/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Pincode<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text"  id="pincode" name="pincode" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                                    </div>
                                    <div class="col-sm-6">
                        				<label>Currency<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="currency" name="currency" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($currency as $val)
                                                <option value="{{$val->id}}">{{$val->currency_code}}</option>
                                            @endforeach
                                        </select><br/>
                        			</div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addVendorForm','post')">Submit</button>
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