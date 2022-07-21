<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Update Order Delivery Status : {{ $order_id }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="updateOrderDeliveryStatus" method="post" action="saveOrderDeliveryStatus?id={{$data->id}}">                                
                                    <div class="card-text">                                        
                                        <div class="card-text">
                                            <div class="col-md-12 row">
                                                <div class="col-md-6">
                                                    <dl class="row">
                                                        <dt class="col-sm-6 text-left">User Name :</dt>
                                                        <dd class="col-sm-6">{{ ($data['user']->name); }}</dd>
                                                    </dl>
                                                    <dl class="row">
                                                        <dt class="col-sm-6 text-left">Packaging Material Name :</dt>
                                                        <dd class="col-sm-6">{{ ($data['packaging_material']->packaging_material_name); }}</dd>
                                                    </dl>
                                                </div>
                                                <div class="col-md-6">
                                                    <dl class="row">                                                                        
                                                        <dt class="col-sm-4 text-left">Vendor Name :</dt>
                                                        <dd class="col-sm-8">{{  $data['vendor']->vendor_name }} </dd>
                                                    </dl>
                                                    <dl class="row">
                                                        <dt class="col-sm-4 text-left">Payment Status :</dt>
                                                        <dd class="col-sm-8">{{ paymentStatus(($data->customer_payment_status)); }}</dd>
                                                    </dl>                                                    
                                                </div>
                                            </div>                                    
                                        </div>
                                    </div>
                    			@csrf
                        		<div class="row">
                        			<div class="col-sm-6">
                        				<label>Delivery Status<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="order_delivery_status" name="order_delivery_status" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($deliveryStatus as $key => $val)
                                                @if($key == $data->order_delivery_status)
                                                    <option value="{{$key}}" selected>{{$val}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$val}}</option>
                                                @endif
                                            @endforeach
                                        </select><br/>
                        			</div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('updateOrderDeliveryStatus','post')">Update</button>
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