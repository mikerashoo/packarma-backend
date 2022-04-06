<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add State</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="addStateForm" method="post" action="saveState">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>State Name<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="state_name" name="state_name"><br/>
                        			</div>                                    
                                    <div class="col-sm-6">
                        				<label>Country Name<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="country" name="country" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($country as $countries)
                                                <option value="{{$countries->id}}">{{$countries->country_name}}</option>
                                            @endforeach
                                        </select><br/>
                        			</div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addStateForm','post')">Submit</button>
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