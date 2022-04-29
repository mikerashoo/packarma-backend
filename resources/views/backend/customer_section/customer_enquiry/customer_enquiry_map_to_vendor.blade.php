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
                                        <div class="col-md-6">
                                            
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">User Name :</dt>
                                                <dd class="col-sm-8">{{ $data['user']->name }}</dd>
                                            </dl>
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">Product Name :</dt>
                                                <dd class="col-sm-8">{{ $data['product']->product_name }}</dd>
                                            </dl>
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">Enquiry Type :</dt>
                                                <dd class="col-sm-8">{{ customerEnquiryType($data->enquiry_type); }}</dd>
                                            </dl>
                                            <dl class="row">                                                                        
                                                <dt class="col-sm-4 text-left">Description :</dt>
                                                <dd class="col-sm-8">{{ $data->description }} </dd>
                                            </dl>
                                        </div>
                                        <div class="col-md-6">
                                            <dl class="row">                                                                        
                                                <dt class="col-sm-4 text-left">Address :</dt>
                                                <dd class="col-sm-8">{{ $data->address }} </dd>
                                            </dl>
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">City Name :</dt>
                                                <dd class="col-sm-8">{{ $data['city']->city_name }}</dd>
                                            </dl>
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">State Name :</dt>
                                                <dd class="col-sm-8">{{ $data['state']->state_name; }}</dd>
                                            </dl>
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">Country Name :</dt>
                                                <dd class="col-sm-8">{{ $data['country']->country_name; }}</dd>
                                            </dl>
                                        </div>                                       
                                    </div>                                    
                                </div>                                
                                @csrf                            
                                <div class="row">                                                                    
                                    <div class="table-responsive">
                                        <table class="table table-stripped" id="vendorMapTbl">
                                            <thead>
                                                <tr>
                                                    <th style="width: 20%;">Vendor Name<span style="color:#ff0000">*</span></th>
                                                    {{-- <th style="width: 18%;">Warehouse<span style="color:#ff0000">*</span></th> --}}
                                                    <th style="width: 18%;">Vendor Price<span style="color:#ff0000">*</span></th>
                                                    <th style="width: 18%;">Commission Rate Per Kg<span style="color:#ff0000">*</span></th>
                                                    <th>Validity in Hours<span style="color:#ff0000">*</span></th>
                                                    <th>ETD<span style="color:#ff0000">*</span></th>
                                                    <th><button type="button" class="btn btn-primary btn-sm" id="addStock"><i class="fa fa-plus"></i></button></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
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
    var trlen = $('#vendorMapTbl tbody tr').length;
    if(trlen == 0)
    {
        var i = trlen;
    }
    else
    {
        var i = parseInt($('#vendorMapTbl tbody tr:last-child').attr('data-key'))+1;
    }
    <?php
    $vendor_drop = '<option value="">Select</option>';
    $warehouse_drop = '<option value="">Select</option>';
    if(is_array($vendor[0])){
        for($i=0; $i<count($vendor); $i++){
            $vendor_drop = $vendor_drop.'<option value="'.$vendor[$i]['id'].'">'.$vendor[$i]['vendor_name'].'</option>';
        }
    }
    // if(is_array($warehouse[0])){
    //     for($i=0; $i<count($warehouse); $i++){
    //         $warehouse_drop = $warehouse_drop.'<option value="'.$warehouse[$i]['id'].'">'.$warehouse[$i]['warehouse_name'].'</option>';
    //     }
    // }
    ?>
    var vendor_dropdown = '<?php echo $vendor_drop ?>';
    var warehouse_dropdown = '<?php echo $warehouse_drop ?>';
    var etddate = new Date().toJSON().slice(0,10);
    $('#vendorMapTbl').append('<tr id="vendorMapTblTr'+i+'" data-key="'+i+'">'+
        '<input class="form-control" type="hidden"  value="<?php echo $data->id; ?>" id="customer_enquiry_id'+i+'" name="customer_enquiry_id[]">'+
        '<input class="form-control" type="hidden"  value="<?php echo $data->product_id; ?>" id="product'+i+'" name="product[]">'+
        '<input class="form-control" type="hidden"  value="<?php echo $data->user_id; ?>" id="user'+i+'" name="user[]">'+
        '<td>'+
            '<select class="select2" id="vendor'+i+'" value="" name="vendor[]" style="width:100%;" onchange="getVendorWarehouse(this.value,'+i+')">'+
                vendor_dropdown+
            '</select>'+
        '</td'+'<br>'+
        // '<td>'+
        //     '<select class="select2" id="warehouse'+i+'" value="" name="warehouse[]" style="width:100%;">'+
        //         warehouse_dropdown+
        //     '</select>'+
        // '</td>'+
        '<td><input class="form-control" type="text" step=".001" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="" id="vendor_price'+i+'" name="vendor_price[]"></td>'+
        '<td><input class="form-control" type="text" step=".001" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="" id="commission_rate'+i+'" name="commission_rate[]"></td>'+
        '<td><input class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46" value="" id="quotation_validity'+i+'" name="quotation_validity[]"></td>'+
        '<td><input class="form-control" type="date" value="'+etddate+'" id="etd'+i+'" name="etd[]"></td>'+
        '<td><button type="button" class="btn btn-danger btn-sm" id="removeVendorMap'+i+'" onclick="remove_vendor_map_tbl_row('+i+')"><i class="fa fa-minus"></i></button></td>'+
    '</tr>');

    $('#vendor'+i).select2();
    $('#warehouse'+i).select2();
});
function remove_vendor_map_tbl_row(i)
{
    $('#vendorMapTblTr'+i).remove();
}

//getVendorWarehouse function with Ajax to get warehouse drop down of selected vendor in customer enquiry map to vendor
function getVendorWarehouse(vendor,i){
        var product_id ='<?php echo $data->product_id; ?>';
        // alert(product_id);
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
                // var vendor_warehouses (response['data']['vendor_warehouse'][0]); 
                var vendor_price = response['data']['vendorMaterialMapData'][0]['vendor_price']; 
                var vommission_rate = response['data']['vendorMaterialMapData'][0]['min_amt_profit'];                
                $("#vendor_price"+i).val(vendor_price);
                $("#commission_rate"+i).val(vendor_price);
            },
        });  
    }
</script>
