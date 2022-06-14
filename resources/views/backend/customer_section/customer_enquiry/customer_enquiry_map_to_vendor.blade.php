<?php #print_r($vendor_material_map); exit; ?>
<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Map Vendors To Customer Enquiry : {{ $data->id }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="customerEnquiryMapToVendorForm" method="post" action="saveEnquiryMapToVendor?id={{ $data->id }}">
                                <div class="card-text">
                                    <div class="col-md-12 row">
                                        <div class="col-md-5">
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">Product Name :</dt>
                                                <dd class="col-sm-8">{{ $data['product']->product_name }}</dd>
                                            </dl>
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">User Name :</dt>
                                                <dd class="col-sm-8">{{ $data['user']->name }}</dd>
                                            </dl>
                                            {{-- <dl class="row">
                                                <dt class="col-sm-4 text-left">Enquiry Type :</dt>
                                                <dd class="col-sm-8">{{ customerEnquiryType($data->enquiry_type); }}</dd>
                                            </dl> --}}
                                            <dl class="row">                                                                        
                                                <dt class="col-sm-4 text-left">User Address :</dt>
                                                <dd class="col-sm-8">{{$data->address}}, {{$data['city']->city_name}}, {{$data['state']->state_name}}, {{$data->pincode}}</dd>
                                            </dl>
                                        </div>
                                        <div class="col-md-7">
                                            {{-- <dl class="row">
                                                <dt class="col-sm-5 text-left">Vendor Name :</dt>
                                                <dd class="col-sm-7">{{ $vendor_material_map['vendor']['vendor_name'] }}</dd>
                                            </dl> --}}
                                            <dl class="row">
                                                <dt class="col-sm-5 text-left">Packaging Material :</dt>
                                                <dd class="col-sm-7">{{$packaging_material['packaging_material_name']}}</dd>
                                            </dl>
                                            <dl class="row">
                                                <dt class="col-sm-5 text-left">Recommendation Engine :</dt>
                                                <dd class="col-sm-7">{{$recommendation_engine['engine_name']}}</dd>
                                            </dl>
                                            <dl class="row">                                                                        
                                                <dt class="col-sm-5 text-left">Description :</dt>
                                                <dd class="col-sm-7 ">{{ $data->description }} </dd>
                                            </dl>
                                        </div>                                       
                                    </div>                                    
                                </div>                                
                                @csrf    
                                <hr>                        
                                <div class="row">                                                                    
                                    <div class="table-responsive" id="vendorMapTbl">
                                                    <button type="button" class="btn btn-primary btn-sm pull-right" id="addStock"><i class="fa fa-plus"></i></button>
<div id="map_section">

</div>
                                        
                                    </div>                                  
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('customerEnquiryMapToVendorForm','post')">Submit</button>
                                            <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
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
$(document).on('click', '#addStock', function(event){
    var trlen = $('.vendorMapTblTr').length;
    if(trlen == 0)
    {
        var i = trlen;
    }
    else
    {
     var i =   parseInt($('#vendorMapTbl div.vendorMapTblTr:last').attr('data-key'))+1;
    }
    <?php
    $vendor_drop = '<option value="" style="width=100%;">Select</option>';
    if(is_array($vendor[0])){
        for($i=0; $i<count($vendor); $i++){
            $vendor_drop = $vendor_drop.'<option value="'.$vendor[$i]['id'].'">'.$vendor[$i]['vendor_name'].'</option>';
        }
    }
    ?>
    var vendor_dropdown = '<?php echo $vendor_drop ?>';
    $('#vendorMapTbl').append('<div class="vendorMapTblTr col-md-12 row" id="vendorMapTblTr'+i+'" data-key="'+i+'">'+
        '<input class="form-control" type="hidden"  value="<?php echo $data->id; ?>" id="customer_enquiry_id'+i+'" name="customer_enquiry_id[]">'+
        '<input class="form-control" type="hidden"  value="<?php echo $data->product_id; ?>" id="product'+i+'" name="product[]">'+
        '<input class="form-control" type="hidden"  value="<?php echo $data->product_quantity; ?>" id="product_quantity'+i+'" name="product_quantity[]">'+
        '<input class="form-control" type="hidden"  value="<?php echo $data->user_id; ?>" id="user'+i+'" name="user[]">'+
        '<div class="col-sm-4"><dl class="row"><label class="col-sm-12 text-left">Vendor Name<span style="color:#ff0000">*</span></label><dd class="col-sm-12">'+
            '<select class="select2" id="vendor'+i+'" value="" name="vendor[]" style="width:100%;" onchange="getVendorWarehouse(this.value,'+i+')">'+
                vendor_dropdown+
            '</select>'+
        '</dd></dl></div>'+
        //    '<div class="col-sm-4"><dl class="row"><label class="col-sm-12 text-left">Vendor Name<span style="color:#ff0000">*</span></label><dd class="col-sm-12">'+
        //     '<select class="select2" id="warehouse'+i+'" value="" name="warehouse[]" style="width:100%;">'+
        //        '<option value="">Select</option>'+
        //     '</select>'+
        // '</dd></dl></div>'+
        '<div class="col-sm-4"><dl class="row"><label class="col-sm-12 text-left">Vendor Price<span style="color:#ff0000">*</span></label><dd class="col-sm-12"><input class="form-control" type="text" step=".001" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="" id="vendor_price'+i+'" name="vendor_price[]"></dd></dl></div>'+
        '<div class="col-sm-4"><dl class="row"><label class="col-sm-12 text-left">Commission Rate Per Kg<span style="color:#ff0000">*</span></label><dd class="col-sm-12"><input class="form-control" type="text" step=".001" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="" id="commission_rate'+i+'" name="commission_rate[]"></dd></dl></div>'+
        '<div class="col-sm-4"><dl class="row"><label class="col-sm-12 text-left">Validity (Hrs)<span style="color:#ff0000">*</span></label><dd class="col-sm-12"><input class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="" id="quotation_validity'+i+'" name="quotation_validity[]"></dd></dl></div>'+
        '<div class="col-sm-4"><dl class="row"><label class="col-sm-12 text-left">Lead Time (Days)<span style="color:#ff0000">*</span></label><dd class="col-sm-12"><input class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="" id="lead_time'+i+'" name="lead_time[]"></dd></dl></div>'+
      '<div class="col-sm-4"><dl class="row"><label class="col-sm-12 text-left">Gst Type<span style="color:#ff0000">*</span></label><dd class="col-sm-12">'+
            '<select class="select2" id="gst_type'+i+'" value="" name="gst_type[]" style="width:100%;" onchange="taxValueToggle(this.value,'+i+')">'+
               '<option value="">Select</option>'+
               '<option value="not_applicable">Not Applicable</option>'+
               '<option value="cgst+sgst">CGST+SGST</option>'+
               '<option value="igst">IGST</option>'+
            '</select>'+
        '</dd></dl></div>'+
        '<div class="col-sm-4"><dl class="row"><label class="col-sm-12 text-left">Gst Percentage<span style="color:#ff0000">*</span></label><dd class="col-sm-12"><input class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="" id="gst_percentage'+i+'" name="gst_percentage[]"></dd></dl></div>'+
        '<div class="col-sm-8"><dl class="row"><dd class="col-sm-12"><button type="button" class="btn btn-danger btn-sm pull-right" id="removeVendorMap'+i+'" onclick="remove_vendor_map_tbl_row('+i+')"><i class="fa fa-minus"></i></button></dd></dl></div>'+
        '</div>');

    $('#vendor'+i).select2();
    $('#warehouse'+i).select2();
    $('#gst_type'+i).select2();
});
function remove_vendor_map_tbl_row(i)
{
    $('#vendorMapTblTr'+i).remove();
}

//getVendorWarehouse function with Ajax to get warehouse drop down of selected vendor in customer enquiry map to vendor
function getVendorWarehouse(vendor,i)
{
        var product_id ='<?php echo $data->product_id; ?>';
        $("#vendor_price"+i).val('');
        $("#commission_rate"+i).val('');
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
                if(response['data']['vendorMaterialMapData'].length !== 0){
                    var vendor_price = response['data']['vendorMaterialMapData'][0]['vendor_price']; 
                    var commission_rate = response['data']['vendorMaterialMapData'][0]['min_amt_profit'];
                }
                if(vendor_price){
                    $("#vendor_price"+i).val(vendor_price);
                    $("#commission_rate"+i).val(commission_rate);
                }else{
                    $("#vendor_price"+i).val('');
                    $("#commission_rate"+i).val('');
                }
                $("#warehouse"+i).empty();
                $("#warehouse"+i).append('<option value="">Select</option>');
                for(var j=0; j<response['data']['vendor_warehouse'].length; j++)
                {
                    var warehouse_id = response['data']['vendor_warehouse'][j]['id'];
                    var warehouse_name = response['data']['vendor_warehouse'][j]['warehouse_name'];
                    $("#warehouse"+i).append('<option value="'+warehouse_id+'">'+warehouse_name+'</option>');
                }
            },
        });  
    }


    function taxValueToggle(gst_type,i){
        if(gst_type == 'not_applicable'){
        $('#gst_percentage'+i).hide('slow');
        }else{
            $('#gst_percentage'+i).show('slow');

        }
       
        
    }
</script>
