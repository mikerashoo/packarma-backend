<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Category : {{$data->sub_category_name}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="editSubCategoryForm" method="post" action="saveSubCategory?id={{$data->id}}">
                                <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                        			<div class="col-sm-6">
                        				<label>Sub Category Name<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="sub_category_name" name="sub_category_name" value="{{$data->sub_category_name}}"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Category<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="category" name="category" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($category as $categories)
                                                @if ($categories->id == $data->category_id)
                                                    <option value="{{$categories->id}}" selected>{{$categories->category_name}}</option>
                                                @else
                                                    <option value="{{$categories->id}}">{{$categories->category_name}}</option>
                                                @endif
                                            @endforeach
                                        </select><br/>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Sub Category Image<span style="color:#ff0000">*</span></label>
                                        <p style="color:blue;">Note : Upload file size <?php echo  config('global.DIMENTIONS.SUB_CATEGORY'); ?></p>
                                        <input class="form-control" type="file" id="sub_category_image" name="sub_category_image" accept="image/png, image/jpg, image/jpeg" onchange="checkFiles(this.files)"><br/>
                                        <img src="{{ $data->image_path}}" width="200px" height="auto">
                                    </div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('editSubCategoryForm','post')">Update</button>
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