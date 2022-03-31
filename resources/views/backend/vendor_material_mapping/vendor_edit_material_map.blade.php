<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Vendor Grade Map</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>                        
                    	<div class="card-body">
                    		<form id="editVendorGradeMapForm" method="post" action="saveVendorGradeMap?id={{ $data->id }}">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6 mb-3">
                        				<label>Vendor Name<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2" id="vendor" name="vendor" style="width: 100% !important;">
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
                        				<label>Grade Name<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2" id="grade" name="grade" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($grade as $grades)
                                                @if ($grades->id == $data->grade_id)
                                                    <option value="{{ $grades->id }}" selected>{{ $grades->grade_name }}</option>
                                                @else
                                                    <option value="{{ $grades->id }}">{{ $grades->grade_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br />
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Commission Rate Per Kg<span style="color:#ff0000">*</span></label>
                        				<input class="form-control" type="number" step=".001" value="{{ $data->min_amt_profit; }}" id="minimum_amount_profit" name="minimum_amount_profit"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Commission Rate Per Quantity<span style="color:#ff0000">*</span></label>
                        				<input class="form-control" type="number" step=".001" value="{{ $data->min_stock_qty; }}" id="minimum_stock_quantity" name="minimum_stock_quantity"><br/>
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
                        					<button type="button" class="btn btn-success" onclick="submitForm('editVendorGradeMapForm','post')">Submit</button>
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