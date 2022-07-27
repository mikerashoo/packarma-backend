<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Product Form : {{$data->product_form_name}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="editProductForm" method="post" action="saveProductForm?id={{$data->id}}">
                                <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                        			<div class="col-sm-6">
                        				<label>Product Form Name<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="product_form_name" name="product_form_name" value="{{$data->product_form_name}}"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Short Description</label>
                        				<input class="form-control" type="text" id="short_description" name="short_description" value="{{$data->short_description}}"><br/>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Product Form Image</label>
                                        <p style="color:blue;">Note : Upload file size <?php echo  config('global.DIMENTIONS.PRODUCT_FORM'); ?></p>
                                        <input class="form-control" type="file" id="product_form_image" name="product_form_image" accept="image/png, image/jpg, image/jpeg" onchange="checkFiles(this.files)"><br/>
                                        <img src="{{ $data->image_path}}" width="200px" height="auto">
                                    </div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('editProductForm','post')">Update</button>
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