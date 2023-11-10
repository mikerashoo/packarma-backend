<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Banner : {{ $data->title }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i
                                            class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <!-- <hr class="mb-0"> -->
                        <div class="card-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a href="#details" role="tab" id="details-tab"
                                        class="nav-link d-flex align-items-center active" data-toggle="tab"
                                        aria-controls="details" aria-selected="true">
                                        <i class="ft-info mr-1"></i>
                                        <!-- <span class="d-none d-sm-block">Details</span> -->
                                        <span class="">Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#page_description" role="tab" id="page_description-tab"
                                        class="nav-link d-flex align-items-center" data-toggle="tab"
                                        aria-controls="page_description" aria-selected="false">
                                        <i class="ft-info mr-1"></i>
                                        <!-- <span class="d-none d-sm-block">SEO description</span> -->
                                        <span class="">SEO description</span>
                                    </a>
                                </li>
                            </ul>
                            <form id="editBannersForm" method="post"
                                action="save_solution_banner?id={{ $data->id }}">
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane fade mt-2 show active" id="details" role="tabpanel"
                                        aria-labelledby="details-tab">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Banner Title<span class="text-danger">*</span></label>
                                                <input class="form-control required" type="text" id="title"
                                                    name="title" value="{{ $data->title }}"><br />




                                                <label>Banners Description</label>
                                                <textarea class="form-control " id="description" name="description">{{ $data->description }}</textarea><br />



                                                <label>Banners Start Date Time</label>
                                                <input class="form-control " type="datetime-local" id="start_date_time"
                                                    value="{{ $data->start_date_time }}" name="start_date_time"><br />

                                                <label>Banners End Date Time</label>
                                                <input class="form-control " type="datetime-local" id="end_date_time"
                                                    value="{{ $data->end_date_time }}" name="end_date_time"><br />
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Banners Link</label>
                                                <input class="form-control " type="text" id="link"
                                                    value="{{ $data->link }}" name="link"><br />

                                                <label>App Page</label>
                                                <select class="select2" id="app_page_id" name="app_page_id"
                                                    value="{{ $data->page->id }}" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    @foreach ($data->appPages as $appPage)
                                                        <option value="{{ $appPage->id }}">
                                                            {{ $appPage->pageName }}</option>
                                                    @endforeach
                                                </select><br /><br>

                                                <label>Products</label>


                                                <select name="product_ids[]" multiple="multiple" class="select2"
                                                    value="{{ $data->products_ids }}" id="product_ids"
                                                    style="width: 100% !important;">
                                                    @foreach ($data->products as $product)
                                                        <option value="{{ $product->id }}">
                                                            {{ $product->product_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label>Banner Image<span class="text-danger">*</span></label>
                                                <p style="color:blue;">Note : Upload file size <?php echo config('global.DIMENTIONS.BANNER'); ?></p>
                                                <input class="form-control" type="file" id="banner_image"
                                                    name="banner_image" accept="image/png, image/jpg, image/jpeg"
                                                    multiple><br />
                                                <img src="{{ $data->image_path }}" width="200px" height="auto">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade mt-2" id="page_description" role="tabpanel"
                                        aria-labelledby="page_description-tab">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Meta Title</label>
                                                <input class="form-control" type="text" id="meta_title"
                                                    name="meta_title" value="{{ $data->meta_title }}"><br />
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Meta Description</label>
                                                <input class="form-control" type="text" id="meta_description"
                                                    name="meta_description"
                                                    value="{{ $data->meta_description }}"><br />
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Meta Keyword</label>
                                                <input class="form-control" type="text" id="meta_keyword"
                                                    name="meta_keyword" value="{{ $data->meta_keyword }}"><br />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-success"
                                                    onclick="submitForm('editBannersForm','post')">Update</button>
                                                <a href="{{ URL::previous() }}"
                                                    class="btn btn-danger px-3 py-1"></i>Cancel</a>
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

<script>
    $('.select2').select2().val({{ $data->product_ids }}).trigger("change");
</script>
