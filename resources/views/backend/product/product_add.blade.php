<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Product</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="addProductForm" method="post" action="saveProduct">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Product Name<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="product_name" name="product_name"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Product Description</label>
                        				<input class="form-control" type="text" id="product_description" name="product_description"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Category<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="category" name="category" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($category as $categories)
                                                <option value="{{$categories->id}}">{{$categories->category_name}}</option>
                                            @endforeach
                                        </select><br/><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Sub Category<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="sub_category" name="sub_category" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            {{-- @foreach($sub_category as $value)
                                                <option value="{{$value->id}}">{{$value->sub_category_name}}</option>
                                            @endforeach --}}
                                        </select><br/><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Product Form<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="product_form" name="product_form" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($product_form as $forms)
                                                <option value="{{$forms->id}}">{{$forms->product_form_name}}</option>
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Packaging Treatment<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="packaging_treatment" name="packaging_treatment" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($packaging_treatment as $treatments)
                                                <option value="{{$treatments->id}}">{{$treatments->packaging_treatment_name}}</option>
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Product Image<span style="color:#ff0000">*</span></label>
                                        <p style="color:blue;">Note : Upload file size <?php echo  config('global.DIMENTIONS.PRODUCT'); ?></p>
                                        <input type="file" id="product_image" name="product_image" class="form-control required" accept="image/png, image/jpg, image/jpeg">
                                    </div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addProductForm','post')">Submit</button>
                                            <a href="{{URL::previous()}}" class="btn btn-danger px-3 py-1"></i>Cancel</a>
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