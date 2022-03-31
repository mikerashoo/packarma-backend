<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                     <h5 class="pt-2">Edit Banner : {{$data->title}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <!-- <hr class="mb-0"> -->
                    	<div class="card-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a href="#details" role="tab" id="details-tab" class="nav-link d-flex align-items-center active" data-toggle="tab" aria-controls="details" aria-selected="true">
                                        <i class="ft-info mr-1"></i>
                                        <!-- <span class="d-none d-sm-block">Details</span> -->
                                        <span class="">Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#page_description" role="tab" id="page_description-tab" class="nav-link d-flex align-items-center" data-toggle="tab" aria-controls="page_description" aria-selected="false">
                                        <i class="ft-info mr-1"></i>
                                        <!-- <span class="d-none d-sm-block">SEO description</span> -->
                                        <span class="">SEO description</span>
                                    </a>
                                </li>
                            </ul>
                            <form id="editBannersForm" method="post" action="saveBanners?id={{$data->id}}">
                            @csrf
                                <div class="tab-content">
                                    <div class="tab-pane fade mt-2 show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group row ">
                                                    <label class="col-md-3 label-control">Type<span class="text-danger">*</span></label>
                                                    <div class="col-md-9">
                                                        <div class="input-group col-md-9">
                                                            <div class="radio d-inline-block mr-2 mb-1">
                                                                <input type="radio" id="customer" name="type" value="Customer" {{$data->type == 'customer' ? 'checked' : ''}}>
                                                                <label for="customer">Customer</label>
                                                            </div>
                                                            <div class="radio d-inline-block">
                                                                <input type="radio" id="vendor" name="type" value="Vendor" {{$data->type == 'vendor' ? 'checked' : ''}}>
                                                                <label for="vendor">Vendor</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Banner Title<span class="text-danger">*</span></label>
                                                <input class="form-control required" type="text" id="title" name="title" value="{{$data->title}}"><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Banner Image<span class="text-danger">*</span></label>
                                                <p style="color:blue;">Note : Upload file size <?php echo  config('global.DIMENTIONS.BANNER'); ?></p>
                                                <input class="form-control required" type="file" id="banner_image" name="banner_image" accept="banner_image/png, banner_image/jpg, banner_image/jpeg" onchange="checkFiles(this.files)" multiple><br/>
                                                <img src="{{ $data->image_path}}" width="200px" height="auto">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade mt-2" id="page_description" role="tabpanel" aria-labelledby="page_description-tab">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Meta Title</label>
                                                <input class="form-control" type="text" id="meta_title" name="meta_title" value="{{$data->meta_title}}"><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Meta Description</label>
                                                <input class="form-control" type="text" id="meta_description" name="meta_description" value="{{$data->meta_description}}"><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Meta Keyword</label>
                                                <input class="form-control" type="text" id="meta_keyword" name="meta_keyword" value="{{$data->meta_keyword}}"><br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-success" onclick="submitForm('editBannersForm','post')">Update</button>
                                                <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                            </div>
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