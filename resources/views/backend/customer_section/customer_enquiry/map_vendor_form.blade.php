<form id="customerEnquiryMapToVendorForm" method="post" action="saveEnquiryMapToVendor">

    <div class="row form-error"></div>
    @csrf
    <input type="hidden" value="{{$vender_quotation_details->id ?? '-1' ;}}" name="id">
       <input class="form-control" type="hidden"  value="{{$customer_enquiry_data->id}}" id="customer_enquiry_id" name="customer_enquiry_id">
        <input class="form-control" type="hidden"  value="{{$customer_enquiry_data->product_id}}" id="product" name="product">
        <input class="form-control" type="hidden"  value="{{$customer_enquiry_data->product_quantity}}" id="product_quantity" name="product_quantity">
        <input class="form-control" type="hidden"  value="{{$customer_enquiry_data->user_id}}" id="user" name="user">
<div class="row col-md-12">
<div class="col-md-12">
    <dl class="row">
        <dt class="col-sm-5 text-left">Select Vendor <span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
            <select class="select2" id="vendor" value="" name="vendor" style="width:100%;" onchange="getVendorWarehouse(this.value)">
                <option value="" style="width=100%;">Select Vendor</option>
                @if(is_array($vendor))
        @foreach($vendor as $ven)
            <option value="{{$ven['id']}}" @isset($vender_quotation_details->vendor_id) {{ ($ven['id'] == $vender_quotation_details->vendor_id) ? 'selected':'';}} @endisset>{{$ven['vendor_name']}}</option>;
        @endforeach
            @endif
            </select>
        </dd>
    </dl>
     <dl class="row">
        <dt class="col-sm-5 text-left">Rate <span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
            <input class="form-control" type="text" step=".001" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="{{$vender_quotation_details->vendor_price ?? '' ;}}" id="vendor_price" name="vendor_price">
        </dd>
    </dl>
     <dl class="row">
        <dt class="col-sm-5 text-left">Commission Rate <span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
           <input class="form-control" type="text" step=".001" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="{{$vender_quotation_details->commission_amt ?? '' ;}}" id="commission_rate" name="commission_rate">
        </dd>
    </dl>
     <dl class="row">
        <dt class="col-sm-5 text-left">Deivery In <span style="color:#ff0000">*</span></dt>
        <dd class="col-sm-7">
            <input class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="{{$vender_quotation_details->lead_time ?? '' ;}}" id="lead_time" name="lead_time">
        </dd>
    </dl>
    <div class="row">
           <div class="col-sm-12">
                <div class="pull-right">
                    <button type="button" class="btn btn-sm btn-success px-3 py-1" onclick="submitModalForm('customerEnquiryMapToVendorForm','post')">Add</button>
                    <a href="javascript:;" class="btn btn-sm btn-primary px-3 py-1 bootbox-close-button">Back</a>
                </div>
            </div>
    </div>                         
</div>
</div>
</form>