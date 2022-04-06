<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Vendor Packaging Material Map</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>                        
                    	<div class="card-body">
                    		<form id="editVendorMaterialMapForm" method="post" action="saveVendorMaterialMap?id={{ $data->id }}">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6 mb-3">
                        				<label>Vendor Name<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="vendor" name="vendor" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($vendor as $vendors)
                                                @if ($vendors->id == $data->vendor_id)
                                                    <option value="{{ $vendors->id }}" selected>{{ $vendors->vendor_name }}</option>
                                                @else
                                                    <option value="{{ $vendors->id }}">{{ $vendors->vendor_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br />
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Packaging Material Name<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="material" name="material" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($packaging_material as $materials)
                                                @if ($materials->id == $data->packaging_material_id)
                                                    <option value="{{ $materials->id }}" selected>{{ $materials->packaging_material_name }}</option>
                                                @else
                                                    <option value="{{ $materials->id }}">{{ $materials->packaging_material_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br />
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Recommendation Engine Name<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="recommendation_engine" value="" name="recommendation_engine" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($recommendation_engine as $engines)
                                                @if ($engines->id == $data->recommendation_engine_id)
                                                    <option value="{{ $engines->id }}" selected>{{ $engines->engine_name }}</option>
                                                @else
                                                    <option value="{{ $engines->id }}">{{ $engines->engine_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br />
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Product Name<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="product" value="" name="product" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($product as $products)
                                                @if ($products->id == $data->product_id)
                                                    <option value="{{ $products->id }}" selected>{{ $products->product_name }}</option>
                                                @else
                                                    <option value="{{ $products->id }}">{{ $products->product_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br />
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Commission Rate Per Kg<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" step=".001" value="{{ $data->min_amt_profit; }}" id="commission_rate_per_kg" name="commission_rate_per_kg" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Commission Rate Per Quantity<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" step=".001" value="{{ $data->min_stock_qty; }}" id="commission_rate_per_qty" name="commission_rate_per_qty" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Vendor Price</label>
                        				<input class="form-control" type="text" step=".001" value="{{ $data->vendor_price; }}" id="vendor_price" name="vendor_price" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>                                    
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('editVendorMaterialMapForm','post')">Submit</button>
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