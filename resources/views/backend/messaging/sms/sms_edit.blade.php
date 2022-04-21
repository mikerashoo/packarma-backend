<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit SMS Operation: {{ $data->operation }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>                        
                    	<div class="card-body">
                    		<form id="editSmsForm" method="post" action="saveSms?id={{$data->id}}">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>User Type<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="user_type" name="user_type" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($messageUserType as $key => $val)
                                                @if($key == $data->user_type)
                                                    <option value="{{$key}}" selected>{{$val}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$val}}</option>
                                                @endif
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Trigger<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="trigger" name="trigger" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($messageTrigger as $key => $val)
                                                @if($key == $data->trigger)
                                                    <option value="{{$key}}" selected>{{$val}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$val}}</option>
                                                @endif
                                            @endforeach
                                        </select><br/><br>
                        			</div>
									<div class="col-sm-12">
                                        <label>Message<span style="color:#ff0000">*</span></label>
                                        <textarea class="form-control required" id="message" name="message" >{{ $data->message }}</textarea><br>
                                    </div> 
                        		</div>
                                <hr>
                                <div class="col-12 col-sm-7 mb-2">
                                    <h5 class="mb-1 text-bold-500"><i class="ft-info"></i> Replacement Options : </h5>
                                </div>
                                <div>
                                    <ul>
                                        <li class="mb-1">$$customername$$ - Replacement for customer name</li>
                                        <li class="mb-1">$$OTP$$ - Replacement for otp</li>
                                        <li class="mb-1">$$amount$$ - Replacement for amount</li>
                                        <li class="mb-1">$$salesid$$ - Replacement for sales id</li>
                                        <li class="mb-1">$$date$$ - Replacement for date</li>
                                        <li class="mb-1">$$amount$$ - Replacement for amount</li>
                                        <li class="mb-1">$$retailername$$ - Replacement for retailer name</li>
                                        <li class="mb-1">$$productid$$ - Replacement for product id</li>
                                        <li class="mb-1">$$amount$$ - Replacement for amount</li>
                                    </ul>
                                </div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('editSmsForm','post')">Submit</button>
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