<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Banners</h5>
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
                            <form id="addBannersForm" method="post" action="saveBanners">
                            @csrf
                                <div class="tab-content">
                                    <div class="tab-pane fade mt-2 show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Banners Title<span class="text-danger">*</span></label>
                                                <input class="form-control required" type="text" id="title" name="title"><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Banner Image<span class="text-danger">*</span></label>
                                                <p style="color:blue;">Note : Upload file size <?php echo  config('global.DIMENTIONS.BANNER'); ?></p>
                                                <input type="file" id="banner_image" name="banner_image" class="form-control required" accept="image/png, image/jpg, image/jpeg, image/svg" onchange="checkFiles(this.files)"><br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade mt-2" id="page_description" role="tabpanel" aria-labelledby="page_description-tab">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Meta Title</label>
                                                <input class="form-control" type="text" id="meta_title" name="meta_title"><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Meta Description</label>
                                                <input class="form-control" type="text" id="meta_description" name="meta_description"><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Meta Keyword</label>
                                                <input class="form-control" type="text" id="meta_keyword" name="meta_keyword"><br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-success" onclick="submitForm('addBannersForm','post')">Submit</button>
                                                <a href="{{URL::previous()}}" class="btn btn-danger px-3 py-1"></i>Cancel</a>
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