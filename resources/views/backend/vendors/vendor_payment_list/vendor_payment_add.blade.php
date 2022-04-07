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
                                            @if (!isset($id))
                                                <option value="">Select</option>
                                            @endif
                                            @foreach ($vendor as $vendors)
                                                @if (isset($id))
                                                    @if ($vendors->id == $id )
                                                        <option value="{{ $vendors->id }}" selected>{{ $vendors->vendor_name }}</option>
                                                    @endif
                                                @else
                                                    <option value="{{ $vendors->id }}">{{ $vendors->vendor_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Order ID<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="order_id" value="" name="order_id" style="width: 100% !important;" onchange="getVendoPaymentDetails(this.value)">
                                            @if (!isset($id))
                                                    <option value="">Select</option>
                                                @endif
                                                @if (isset($id))
                                                    @foreach ($order as $orders)
                                                        @if ($orders->id == $id )
                                                            @php
                                                                $total_amount = $orders->grand_total;
                                                                $pending_amount = $orders->vendor_pending_payment;
                                                            @endphp
                                                            <option value="{{ $orders->id }}" selected>{{ $orders->id }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            {{-- <option value="">Select</option> --}}
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Grand Total Amount</label>
                                        @if (isset($id))
                                            <input class="form-control" type="text" id="grand_total" disabled value="{{ $total_amount }}" style="border:0;"><br/>
                                        @else
                                            <input class="form-control" type="text" id="grand_total" disabled value="" style="border:0;"><br/>
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Pending Amount</label>
                                        @if (isset($id))
                                            <input class="form-control" type="text" id="vendor_pending_amount" disabled value="{{ $pending_amount }}" style="border:0;"><br/>
                                        @else
                                            <input class="form-control" type="text" id="vendor_pending_amount" disabled value="" style="border:0;"><br/>
                                        @endif
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
                                    <div class="col-sm-6">
                                        <label>Transaction Date</label>
                                        <input class="form-control" type="date" id="transaction_date" name="transaction_date"/><br>
                                    </div>
                                    <div class="col-sm-6">
                        				<label>Remark</label>
                        				<textarea class="form-control" id="remark" name="remark"></textarea><br/>
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
                        <div class="col-12 col-xl-12 users-module">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-2" id="payment_history">
                                    <thead>
                                        <tr>
                                            <th>Vendor Name</th>
                                            <th>Order ID</th>
                                            <th>Payment Mode</th>
                                            <th>Amount</th>
                                            <th>Transaction Date</th>
                                            <th>Remark</th>
                                            <th>Date Time</th>
                                        </tr>
                                    </thead>
                                    <tbody id="trans_history_table">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $('.select2').select2();
    //getVendorOrders function with Ajax to get order id drop down of selected vendor
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

    //function to get amount values of selected order from drop down
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
    });

    //function to get vendor payment history of selected order id
    function getVendoPaymentDetails(order_id){
        $.ajax({
            url:"getOrderDetailsTableData",
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                 order_id: order_id
            },
            dataType:"JSON",
            success:function(result)
            {
                console.log(result.payment_details);
                // $payment_history = '';
                if(result.payment_details.length > 0){
                    $.each(result.payment_details,function(key,value){
                        $("#trans_history_table").append("<tr>"+
                            "<td>"+value.vendor_id+"</td>"+
                            "<td>"+value.order_id+"</td>"+
                            "<td>"+value.payment_mode+"</td>"+
                            "<td>"+value.amount+"</td>"+
                            "<td>"+value.transaction_date+"</td>"+
                            "<td>"+value.remark+"</td>"+
                            "<td>"+value.updated_at+"</td>"+"</tr>");
                    });
                }else{
                    $('#trans_history_table .select2').val('').trigger('change');
                    // $("#trans_history_table").val('No data found');
                    // $("#trans_history_table").append("<tr>"+"<td colspan='7' class='text-center>"+'No Transaction History Found for Selected ID'+"</td>"+"</tr>");
                    alert('bye');
                }
            },
        });  
    }

    $(document).on('change', '#order_id', function(event){
        event.preventDefault();
        var trans_history_table = $(this).select2().find(":selected").attr('trans_history_table');;
        // var grandTotal = $(this).select2().find(":selected").data("total");
        if(trans_history_table !=  undefined){
            $("#trans_history_table").append("<tr>"+
                            "<td>"+value.vendor_id+"</td>"+
                            "<td>"+value.order_id+"</td>"+
                            "<td>"+value.payment_mode+"</td>"+
                            "<td>"+value.amount+"</td>"+
                            "<td>"+value.transaction_date+"</td>"+
                            "<td>"+value.remark+"</td>"+
                            "<td>"+value.updated_at+"</td>"+"</tr>");
        }else{
            $('#grand_total').val('');
            $('#vendor_pending_amount').val('');
        }
        $('#trans_history_table').toggle();
    });
</script>