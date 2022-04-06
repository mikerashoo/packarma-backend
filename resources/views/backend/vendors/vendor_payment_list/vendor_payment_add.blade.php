<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Vendor Payment Status</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="updateVendorPaymentStatus" method="post" action="saveVendorPaymentStatus">                                
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Vendor<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="vendor" value="" name="vendor" style="width: 100% !important;"  onchange="getVendorOrders(this.value)">
                                            <option value="">Select</option>
                                            @foreach ($vendor as $vendors)
                                                    <option value="{{ $vendors->id }}">{{ $vendors->vendor_name }}</option>
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Order ID<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="order_id" value="" name="order_id" style="width: 100% !important;" >
                                            <option value="">Select</option>
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Grand Total Amount</label>
                                        <input class="form-control" type="text" id="grand_total" disabled value="" style="border:0;"><br/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Pending Amount</label>
                                        <input class="form-control" type="text" id="vendor_pending_amount" disabled value="" style="border:0;"><br/>
                                    </div>
                        			<div class="col-sm-6">
                        				<label>Payment Status<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="payment_status" name="payment_status" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($paymentStatus as $key => $val)
                                                <option value="{{$key}}">{{$val}}</option>
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Payment Mode<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="payment_mode" name="payment_mode" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($paymentMode as $key => $val)
                                                <option value="{{$key}}">{{$val}}</option>
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Amount<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="amount" name="amount" step=".001" value="" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                                    </div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('updateVendorPaymentStatus','post')">Update</button>
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
    function getVendorOrders(vendor_id){
        $.ajax({
            url:"getVendorOrderDropdown",
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                 vendor_id: vendor_id
            },
            dataType:"JSON",
            success:function(result)
            {
                // console.log(result.vendor_orders);
                $dropdownData = '<option value="">Select</option>';
                if(result.vendor_orders.length > 0){
                    $.each(result.vendor_orders,function(key,value){
                        $dropdownData +='<option value="'+value.id+'" data-total="'+value.grand_total+'" data-amt="'+value.vendor_pending_payment+'">'+value.id+'</option>';
                    });
                    $("#order_id").html($dropdownData);
                }else{
                    $("#order_id").html( $dropdownData );
                }
            },
        });  
    } 
    $(document).on('change', '#order_id', function(event){
        event.preventDefault();
        // var amount = $(this).attr('data-amt');
        var amount = $(this).select2().find(":selected").data("amt");
        var grandTotal = $(this).select2().find(":selected").data("total");
        if(amount !=  undefined){
            $('#grand_total').val(grandTotal);
            $('#vendor_pending_amount').val(amount);
        }else{
            $('#grand_total').val('');
            $('#vendor_pending_amount').val('');
        }
        
        // console.log(amount);
    });
</script>