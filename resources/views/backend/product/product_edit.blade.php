<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Product : {{ $data->product_name }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i
                                            class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="editProductForm" method="post" action="saveProduct?id={{ $data->id }}">
                                <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Product Name<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="product_name"
                                            name="product_name" value="{{ $data->product_name }}"><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Product Description</label>
                                        <input class="form-control" type="text" id="product_description"
                                            name="product_description" value="{{ $data->product_description }}"><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Category<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="category" name="category"
                                            style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($category as $categories)
                                                @if ($categories->id == $data->category_id)
                                                    <option value="{{ $categories->id }}" selected>
                                                        {{ $categories->category_name }}</option>
                                                @else
                                                    <option value="{{ $categories->id }}">
                                                        {{ $categories->category_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br /><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Sub Category<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="sub_category" name="sub_category"
                                            value="" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($sub_category as $value)
                                                @if ($value->id == $data->sub_category_id)
                                                    <option value="{{ $value->id }}" selected>
                                                        {{ $value->sub_category_name }}</option>
                                                @else
                                                    <option value="{{ $value->id }}">
                                                        {{ $value->sub_category_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br /><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Product Form<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="product_form" name="product_form"
                                            style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($product_form as $forms)
                                                @if ($forms->id == $data->product_form_id)
                                                    <option value="{{ $forms->id }}" selected>
                                                        {{ $forms->product_form_name }}</option>
                                                @else
                                                    <option value="{{ $forms->id }}">
                                                        {{ $forms->product_form_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br /><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Packaging Treatment<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="packaging_treatment"
                                            name="packaging_treatment" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($packaging_treatment as $treatments)
                                                @if ($treatments->id == $data->packaging_treatment_id)
                                                    <option value="{{ $treatments->id }}" selected>
                                                        {{ $treatments->packaging_treatment_name }}</option>
                                                @else
                                                    <option value="{{ $treatments->id }}">
                                                        {{ $treatments->packaging_treatment_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br /><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Banner<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="banner" name="banner"
                                            style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($banner as $banner)
                                                @if ($banner->id == $data->banner_id)
                                                    <option value="{{ $banner->id }}" selected>{{ $banner->title }}
                                                    </option>
                                                @else
                                                    <option value="{{ $banner->id }}">{{ $banner->title }}</option>
                                                @endif
                                            @endforeach
                                        </select><br /><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Measurement Unit<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="unit" name="unit"
                                            style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($measurement_units as $units)
                                                @if ($units->id == $data->unit_id)
                                                    <option value="{{ $units->id }}" selected>
                                                        {{ $units->unit_symbol }}</option>
                                                @else
                                                    <option value="{{ $units->id }}">{{ $units->unit_symbol }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select><br /><br />
                                    </div>

                                    <div class="col-sm-6">
                                        <label>Product Image<span style="color:#ff0000">*</span></label>
                                        <p style="color:blue;">Note : Upload file size <?php echo config('global.DIMENTIONS.PRODUCT'); ?></p>
                                        <input class="form-control" type="file" id="product_image"
                                            name="product_image" accept="image/png, image/jpg, image/jpeg">
                                        <img src="{{ $data->image_path }}" width="200px" height="auto">
                                    </div>


                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success"
                                                onclick="submitForm('editProductForm','post')">Submit</button>
                                            <a href="{{ URL::previous() }}"
                                                class="btn btn-danger px-3 py-1"></i>Cancel</a>
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
