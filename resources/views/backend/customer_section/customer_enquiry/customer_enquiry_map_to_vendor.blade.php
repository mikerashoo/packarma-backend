<?php #print_r($mapped_vendor); exit; ?>
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
                            <div class="card-text">
                                <div class="col-md-12 row">
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-4 text-left">Product Name:</dt>
                                            <dd class="col-sm-8">{{ $data['product']->product_name }}</dd>
                                        </dl>
                                        <dl class="row">
                                            <dt class="col-sm-4 text-left">User Name:</dt>
                                            <dd class="col-sm-8">{{ $data['user']->name }}</dd>
                                        </dl>
                                        <dl class="row">                                                                        
                                            <dt class="col-sm-4 text-left">User Address:</dt>
                                            <dd class="col-sm-8">{{$data->flat}}, {{$data->land_mark}}, {{$data->area}}, {{$data->city_name}}, {{$data->state->state_name}}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-5 text-left">Packaging Material:</dt>
                                            <dd class="col-sm-7">{{$data['packaging_material']->packaging_material_name}}</dd>
                                        </dl>
                                        <dl class="row">
                                            <dt class="col-sm-5 text-left">Recommendation Engine:</dt>
                                            <dd class="col-sm-7">{{$data['recommendation_engine']->engine_name}}</dd>
                                        </dl>
                                    </div>                                       
                                </div>   
                                
                                <div class="col-md-12 row">
                                    <div class="col-md-12">
                                        <dl class="row">                                                                        
                                            <dt class="col-sm-5 text-left">Map Vendors:</dt>
                                        </dl>
                                    </div>                                       
                                </div>
                                <div class="col-md-12 row">
                                <!-- Outline variants section start -->
                                    <div class="col-md-3 col-12">
                                        <div class="card card-outline-secondary box-shadow-0 text-center" style="height: 90%;">
                                            <div class="card-content">
                                                <div class="card-body modal_src_data" data-size="medium" data-title="Map Vendor To Enquiry" href="map_vendor_form/-1/{{ $data->id }}">
                                                    <h1><i class="fa fa-user fa-2x text-secondary" style="color: grey;"></i></h1>
                                                    <h4 class="card-title text-secondary">Map Vendors</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $i=1;
                                    @endphp
                                    @foreach ($mapped_vendor as $vendors)
                                    <div class="col-md-3 col-12 map_vendor_section">
                                        <div class="card card-outline-secondary box-shadow-0 h6" style="height: 90%;">
                                            <div class="card-content">
                                                <div class="card-body pb-0">
                                                    <h6>{{ $vendors->vendor_name ??''; }}</h6>
                                                    <p class="text-secondary small">Rate, {{ $vendors->vendor_price ??''; }}/{{ $vendors->unit_symbol ??''; }}</p>
                                                    <p class="text-secondary small">Delivery in {{ $vendors->lead_time ??''; }} Days</p>
                                                    <p class="text-secondary small">Commission Rate, {{ $vendors->commission_amt ??''; }}/{{ $vendors->unit_symbol ??''; }}</p>
                                                </div>
                                                <div class="card-footer">
                                                <a href="map_vendor_form/{{$vendors->id}}/{{ $data->id }}" class="modal_src_data" data-size="medium" data-title="Edit Mapped Vendor" style="color: #975AFF;">Edit</a> @if($vendors->enquiry_status != 'quoted' && $vendors->enquiry_status != 'accept') | <a style="color: red;" class="delete_map_vendor" data-id="{{$vendors->id}}" data-url="delete_map_vendor" id="delete{{$i}}" >Remove</a> @endif
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $i++;
                                    @endphp
                                    @endforeach
                                    <!-- Outline variants section end -->
                                </div>
                            </div>                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>

//getVendorWarehouse function with Ajax to get warehouse drop down of selected vendor in customer enquiry map to vendor
function getVendorWarehouse(vendor,i)
{
        var product_id ='<?php echo $data->product_id; ?>';
        $("#vendor_price").val('');
        $("#commission_rate").val('');
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
                    $("#vendor_price").val(vendor_price);
                    $("#commission_rate").val(commission_rate);
                }else{
                    $("#vendor_price").val('');
                    $("#commission_rate").val('');
                }
                $("#warehouse").empty();
                $("#warehouse").append('<option value="">Select</option>');
                for(var j=0; j<response['data']['vendor_warehouse'].length; j++)
                {
                    var warehouse_id = response['data']['vendor_warehouse'][j]['id'];
                    var warehouse_name = response['data']['vendor_warehouse'][j]['warehouse_name'];
                    var warehouse_state_id = response['data']['vendor_warehouse'][j]['state_id'];
                    $("#warehouse").append('<option value="'+warehouse_id+"|"+warehouse_state_id+'" warehouse_state_id ="'+warehouse_state_id+'">'+warehouse_name+'</option>');
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
