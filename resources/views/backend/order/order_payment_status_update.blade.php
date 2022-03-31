<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Update Order Payment Status : {{ $data->id; }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="updateOrderPaymentData" method="post" action="orderUpdatePayment?id={{$data->id}}">                                
                                    <div class="card-text">                                        
                                        <div class="card-text">
                                            <div class="col-md-12 row">
                                                <div class="col-md-6">
                                                    <dl class="row">                                                                        
                                                        <dt class="col-sm-4 text-left">Vendor Name :</dt>
                                                        <dd class="col-sm-8">{{  $data['vendor']->vendor_name }} </dd>
                                                    </dl>
                                                    <dl class="row">
                                                        <dt class="col-sm-4 text-left">Grade Name :</dt>
                                                        <dd class="col-sm-8">{{ ($data['grade']->grade_name); }}</dd>
                                                    </dl>
                                                    <dl class="row">
                                                        <dt class="col-sm-4 text-left">Grand Total Amount :</dt>
                                                        <dd class="col-sm-8">{{ $data->grand_total; }}</dd>
                                                    </dl>
                                                </div>
                                                <div class="col-md-6">
                                                    <dl class="row">
                                                        <dt class="col-sm-4 text-left">User Name :</dt>
                                                        <dd class="col-sm-8">{{ ($data['user']->name); }}</dd>
                                                    </dl>
                                                    <dl class="row">
                                                        <dt class="col-sm-4 text-left">Delivery Status :</dt>
                                                        <dd class="col-sm-8">{{ deliveryStatus(($data->order_delivery_status)); }}</dd>
                                                    </dl>
                                                    <dl class="row">
                                                        <dt class="col-sm-4 text-left">Pending Payment :</dt>
                                                        <dd class="col-sm-8">{{ $data->pending_payment; }}</dd>
                                                    </dl>                                                    
                                                </div>
                                            </div>                                    
                                        </div>
                                    </div>
                    			@csrf
                        		<div class="row">
                        			<div class="col-sm-6">
                        				<label>Payment Status<span style="color:#ff0000">*</span></label>
                        				<select class="select2" id="payment_status" name="payment_status" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($paymentStatus as $key => $val)
                                                @if($key == $data->order_delivery_status)
                                                    <option value="{{$key}}" selected>{{$val}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$val}}</option>
                                                @endif
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Payment Mode<span style="color:#ff0000">*</span></label>
                        				<select class="select2" id="payment_mode" name="payment_mode" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($paymentMode as $key => $val)
                                                @if($key == $data->payment_mode)
                                                    <option value="{{$key}}" selected>{{$val}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$val}}</option>
                                                @endif
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Amount<span style="color:#ff0000">*</span></label>
                                        <input class="form-control" type="text" id="amount" name="amount" step=".001" value="" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Upload Image</label>
                                        <input class="form-control" type="file" id="order_image" name="order_image" accept="image/png, image/jpg, image/jpeg" onchange="checkFiles(this.files)" multiple><br/>
                                    </div>
                                    <input class="form_control" type="hidden" id="user_id" name="user_id" value="{{ $data->user_id; }}">
                                    <input class="form_control" type="hidden" id="grade_id" name="grade_id" value="{{ $data->grade_id; }}">
                                    <input class="form_control" type="hidden" id="vendor_id" name="vendor_id" value="{{ $data->vendor_id; }}">
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('updateOrderPaymentData','post')">Update</button>
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