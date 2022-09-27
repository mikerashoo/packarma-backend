@if($view_only)

@php
$readonly = 'disabled';
@endphp

@else

@php
$readonly = '';
@endphp

@endif


<form id="customerEnquiryMapToVendorForm" method="post" action="saveEnquiryMapToVendor">

    <div class="row form-error"></div>
    @csrf
    <input type="hidden" value="{{$vender_quotation_details->id ?? '-1' ;}}" name="id">
       <input class="form-control" type="hidden"  value="{{$customer_enquiry_data->id}}" id="customer_enquiry_id" name="customer_enquiry_id">
        <input class="form-control" type="hidden"  value="{{$customer_enquiry_data->product_id}}" id="product" name="product">
        <input class="form-control" type="hidden"  value="{{$customer_enquiry_data->product_quantity}}" id="product_quantity" name="product_quantity">
        <input class="form-control" type="hidden"  value="{{$customer_enquiry_data->user_id}}" id="user" name="user">
        <input class="form-control" type="hidden"  value="{{$customer_enquiry_data->state_id}}" id="enquiry_state_id" name="enquiry_state_id">
        <input class="form-control" type="hidden"  value="{{$vender_quotation_details->gstin ?? ''}}" id="vendor_gstin" name="vendor_gstin">
<div class="row col-md-12">
<div class="col-md-12">
    <dl class="row">
        <dt class="col-sm-5 text-left">Select Vendor <span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
            <select class="select2" id="vendor" value="" name="vendor" style="width:100%;" onchange="getVendorWarehouse(this.value)" {{$readonly}}>
                <option value="" style="width=100%;">Select Vendor</option>
                {{-- @if(is_array($vendor)) --}}
        @foreach($vendor as $ven)
            <option value="{{$ven->id}}" @isset($vender_quotation_details->vendor_id) {{ ($ven->id == $vender_quotation_details->vendor_id) ? 'selected':'';}} @endisset>{{$ven->vendor_name}}</option>;
        @endforeach
            {{-- @endif --}}
            </select>
        </dd>
    </dl>
    <dl class="row">
        <dt class="col-sm-5 text-left">Vendor Warehouse</dt>
        <dd class="col-sm-7">
            <select class="select2" id="warehouse" value="" name="warehouse" style="width:100%;" {{$readonly}}>
               <option value="">Select</option>
             </select>
        </dd>
    </dl>
    <dl class="row">
        <dt class="col-sm-5 text-left">Total Vendor Price<span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
            <input class="form-control required" type="text" step=".001" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="{{$vender_quotation_details->vendor_price ?? '' ;}}" id="vendor_price_bulk" name="vendor_price_bulk" {{$readonly}}>
        </dd>
        {{-- <dd id="mapping_price_per_kg" class="form-control col-sm-7" readonly></dd> --}}
    </dl>
    <dl class="row" id="vendor_price_per_kg_div" style="display: none;">
        <dt class="col-sm-5 text-left">Vendor Price/Kg</dt>
        <dd class="col-sm-7" id="vendor_price" readonly></dd>
    </dl>
    <dl class="row">
        <dt class="col-sm-5 text-left">Add Admin Commission Price <span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
           <input class="form-control required" type="text" step=".001" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="{{$vender_quotation_details->commission_amt ?? '' ;}}" id="commission_rate_bulk" name="commission_rate_bulk" {{$readonly}}>
        </dd>
    </dl>
    <dl class="row" id="commission_price_per_kg_div" style="display: none;">
        <dt class="col-sm-5 text-left">Commission/Kg</dt>
        <dd class="col-sm-7" id="commission_rate" readonly></dd>
    </dl>
    <dl class="row">
        <dt class="col-sm-5 text-left">Delivery in Days <span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
           <input class="form-control required" type="text" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="{{$vender_quotation_details->delivery_in_days ?? '7' ;}}" id="delivery_in_days" name="delivery_in_days" {{$readonly}}>
        </dd>
    </dl>
    <dl class="row">
        <dt class="col-sm-5 text-left">Delivery Charges <span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
           <input class="form-control required" type="text" step=".01" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="{{$vender_quotation_details->delivery_charges ?? '' ;}}" id="delivery_charges" name="delivery_charges" {{$readonly}}>
        </dd>
    </dl>
     {{-- <dl class="row">
        <dt class="col-sm-5 text-left">Delivery In <span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
            <input class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="{{$vender_quotation_details->lead_time ?? '' ;}}" id="lead_time" name="lead_time">
        </dd>
    </dl> --}}
    <dl class="row">
        <dt class="col-sm-5 text-left">GST <span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
            <ul class="list-unstyled mb-0">
                <li class="d-inline-block mr-2">
                    <div class="radio">
                        <input type="radio" name="gst_type" id="applicable" @if (isset($vender_quotation_details->gst_type)) {{($vender_quotation_details->gst_type == 'cgst+sgst' || $vender_quotation_details->gst_type == 'igst') ? 'checked':'';}} @else {{'checked'}} @endif class="gst_type" onclick="taxValueToggle('applicable')" value="applicable">
                        <label for="applicable">Yes</label>
                    </div>
                </li>
                <li class="d-inline-block mr-2">
                    <div class="radio">
                        <input type="radio" name="gst_type" id="not_applicable" class="gst_type" onclick="taxValueToggle('not_applicable')" value="not_applicable" @isset($vender_quotation_details->gst_type) {{ ($vender_quotation_details->gst_type == 'not_applicable') ? 'checked':'';}} @endisset {{$readonly}}>
                        <label for="not_applicable">No</label>
                    </div>
                </li>
            </ul>
       </dd>
    </dl>
     <dl class="row" id="gst_percentage_div">
        <dt class="col-sm-5 text-left">Gst Pecentage <span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
            <input class="form-control" type="text" inputmode="numeric" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="{{$vender_quotation_details->gst_percentage ?? '18' ;}}" id="gst_percentage" name="gst_percentage" min=0 max=100>
        </dd>
    </dl>
    <div class="row">
           <div class="col-sm-12">
                <div class="pull-right">
                    @if(!$view_only)
                    <button type="button" class="btn btn-sm btn-success px-3 py-1" onclick="submitModalForm('customerEnquiryMapToVendorForm','post')">Add</button>
                    @endif
                    <a href="javascript:;" class="btn btn-danger px-3 py-1 bootbox-close-button">Cancel</a>
                </div>
            </div>
    </div>                         
</div>
</div>
</form>
<script>

if ($('#not_applicable:checked').length > 0) {
    taxValueToggle('not_applicable')
}

vendor = $("#vendor option:selected").val();
if(vendor != ''){
    getVendorWarehouseForEdit(vendor);
}

function getVendorWarehouseForEdit(vendor)
{
        var product_id ='<?php echo $customer_enquiry_data->product_id; ?>';
        var vendor_warehouse_id ='<?php echo $vender_quotation_details->vendor_warehouse_id ?? 0; ?>';
      
        $.ajax({
            url:"getVendorWarehouseDropdown",
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                vendor_id: vendor, product_id: product_id,
            },
            success:function(result)
            {
                response = JSON.parse(result);
                $("#warehouse").empty();
                $("#warehouse").append('<option value="">Select</option>');
                for(var j=0; j<response['data']['vendor_warehouse'].length; j++)
                {
                    var warehouse_id = response['data']['vendor_warehouse'][j]['id'];
                    var warehouse_name = response['data']['vendor_warehouse'][j]['warehouse_name'];
                    var warehouse_state_id = response['data']['vendor_warehouse'][j]['state_id'];
                    if(vendor_warehouse_id == warehouse_id){
                    $("#warehouse").append('<option value="'+warehouse_id+"|"+warehouse_state_id+'" selected warehouse_state_id ="'+warehouse_state_id+'" >'+warehouse_name+'</option>');
                    }else{
                    $("#warehouse").append('<option value="'+warehouse_id+"|"+warehouse_state_id+'" warehouse_state_id = "'+warehouse_state_id+'">'+warehouse_name+'</option>');
                    }
                }
            },
        });  
    }

    function taxValueToggle(gst_type){
        if(gst_type == 'not_applicable'){
        $('#gst_percentage_div').hide('slow');
        }else{
            $('#gst_percentage_div').show('slow');
        } 
    }

    //added by : Pradyumn, at : 27-Sept-2022, calling js function for price per kg in custome ajax
    $(document).ready(function () {
        var vendor_price = <?php   echo $vender_quotation_details->vendor_price ?? 0 ; ?>;
        var commission_amt = <?php echo $vender_quotation_details->commission_amt ?? 0 ; ?>;
        var product_quantity = <?php echo $vender_quotation_details->product_quantity ?? 0 ; ?>;
        vendorPriceKg(vendor_price, product_quantity);
        commissionPerKg(commission_amt, product_quantity);
    });
</script>