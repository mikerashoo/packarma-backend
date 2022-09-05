<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Country</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>                       
                    	<div class="card-body">
                    		<form id="addCountryForm" method="post" action="saveCountry">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Country Name<span class="text-danger">*</span></label>
                        				<input class="form-control required" type="text" id="country_name" name="country_name"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Phone Code<span class="text-danger">*</span></label>
                        				<input class="form-control required" type="text" id="phone_code" name="phone_code" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Phone Length<span class="text-danger">*</span></label>
                        				<input class="form-control required" type="number" id="phone_length" name="phone_length" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Currency<span class="text-danger">*</span></label>
                        				<select class="form-control select2 required" id="currency_id" name="currency_id" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($data as $currency)
                                                <option value="{{$currency->id}}">{{$currency->currency_code}}</option>
                                            @endforeach
                                        </select><br/>
                        			</div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addCountryForm','post')">Submit</button>
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