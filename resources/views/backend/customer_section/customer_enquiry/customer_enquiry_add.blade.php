<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Customer Enquiry</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>                        
                    	<div class="card-body">
                    		<form id="addCustomerEnquiryForm" method="post" action="saveCustomerEnquiry">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6 mb-3">
                        				<label>Description<span style="color:#ff0000">*</span></label>
                        				<textarea class="form-control required" id="description" value="" name="description"></textarea>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>User<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="user" value="" name="user" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($user as $users)
                                                <option value="{{ $users->id }}">{{ $users->name }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    {{-- <div class="col-sm-6 mb-3">
                        				<label>Order ID<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" value=""  id="order_id" name="order_id"/>
                        			</div> --}}
                                    <div class="col-sm-6 mb-3">
                        				<label>Category<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="category" value="" name="category" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($category as $categories)
                                                <option value="{{ $categories->id }}">{{ $categories->category_name }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Sub Category<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="sub_category" value="" name="sub_category" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($sub_category as $sub_categories)
                                                <option value="{{ $sub_categories->id }}">{{ $sub_categories->sub_category_name }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Product<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="product" name="product" value=""  style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($product as $products)
                                                <option value="{{ $products->id }}">{{ $products->product_name }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                                        <label>Product Weight<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" value=""  id="product_weight" name="product_weight" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'/>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Measurement Unit<span style="color:#ff0000">*</span></label>
                                        <select class="form-control select2 required" id="measurement_unit" name="measurement_unit" value=""  style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($measurement_unit as $units)
                                                <option value="{{ $units->id }}">{{ $units->unit_symbol }}</option>
                                            @endforeach
                                        </select> 
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Product Quantity<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" value=""  id="product_quantity" name="product_quantity" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Shelf Life<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" value=""  id="shelf_life" name="shelf_life" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'/>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Storage Condition<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="storage_condition" name="storage_condition" value=""  style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($storage_condition as $conditions)
                                                <option value="{{ $conditions->id }}">{{ $conditions->storage_condition_title }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Packaging Machine<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="packaging_machine" name="packaging_machine" value=""  style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($packaging_machine as $machines)
                                                <option value="{{ $machines->id }}">{{ $machines->packaging_machine_name }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Product Form<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="product_form" name="product_form" value=""  style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($product_form as $forms)
                                                <option value="{{ $forms->id }}">{{ $forms->product_form_name }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Packing Type<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="packing_type" name="packing_type" value=""  style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($packing_type as $types)
                                                <option value="{{ $types->id }}">{{ $types->packing_name }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Packaging Treatment<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="packaging_treatment" name="packaging_treatment" value=""  style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($packaging_treatment as $treatments)
                                                <option value="{{ $treatments->id }}">{{ $treatments->packaging_treatment_name }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>Quote Type<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="quote_type" name="quote_type" value=""  style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($quote_type as $key => $val)
                                                <option value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                                    <div class="col-sm-6 mb-3">
                        				<label>User Address<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2 required" id="user_address" value="" name="user_address" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($user_address as $userAddress)
                                                    <option value="{{ $userAddress->id }}">{{ $userAddress->address }}</option>
                                            @endforeach
                                        </select>
                        			</div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addCustomerEnquiryForm','post')">Submit</button>
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