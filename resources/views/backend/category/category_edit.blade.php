<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Category : {{$data->category_name}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="editCategoryForm" method="post" action="saveCategory?id={{$data->id}}">
                                <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                        			<div class="col-sm-6">
                        				<label>Category Name<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="category_name" name="category_name" value="{{$data->category_name}}"><br/>
                        			</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Category Image (Selected)<span style="color:#ff0000">*</span></label>
                                        <p style="color:blue;">Note : Upload file size <?php echo  config('global.DIMENTIONS.CATEGORY'); ?></p>
                                        <input class="form-control" type="file" id="category_image" name="category_image" accept="image/png, image/jpg, image/jpeg"><br/>
                                        <img src="{{ $data->image_path}}" width="200px" height="auto">
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Category Image (Un-Selected)<span style="color:#ff0000">*</span></label>
                                        <p style="color:blue;">Note : Upload file size <?php echo  config('global.DIMENTIONS.CATEGORY'); ?></p>
                                        <input class="form-control" type="file" id="category_unselected_image" name="category_unselected_image" accept="image/png, image/jpg, image/jpeg"><br/>
                                        <img src="{{ $data->unselected_image_path}}" width="200px" height="auto">
                                    </div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('editCategoryForm','post')">Update</button>
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