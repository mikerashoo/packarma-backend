<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Packaging Solution</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="addPackagingSolution" method="post" action="savePackagingSolution">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a href="#engine_details" role="tab" id="engine_details-tab" class="nav-link d-flex align-items-center active" data-toggle="tab" aria-controls="engine_details" aria-selected="true">
                                            <i class="ft-info mr-1"></i>
                                            <span class="">Engine Details</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#product_details" role="tab" id="product_details-tab" class="nav-link d-flex align-items-center" data-toggle="tab" aria-controls="product_details" aria-selected="false">
                                            <i class="ft-info mr-1"></i>
                                            <span class="">Product Details</span>
                                        </a>
                                    </li>
                                </ul>
                                {{-- <h4 class="form-section"><i class="ft-info"></i> Details</h4> --}}
                    			@csrf
                        		<div class="tab-content">
                                    <div class="tab-pane fade mt-2 show active" id="engine_details" role="tabpanel" aria-labelledby="engine_details-tab">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Packaging Solution Name<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="packaging_solution" name="packaging_solution"><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Structure Type<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="structure_type" name="structure_type"><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Storage Condition<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="storage_condition" name="storage_condition" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    @foreach($storage_condition as $conditions)
                                                        <option value="{{$conditions->id}}">{{$conditions->storage_condition_title}}</option>
                                                    @endforeach
                                                </select><br/><br/>
                                            </div>
                                            {{-- <div class="col-sm-6">
                                                <label>Minimum Shelf Life<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="min_shelf_life" name="min_shelf_life" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Maximum Shelf Life<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="max_shelf_life" name="max_shelf_life" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                                            </div> --}}
                                            <div class="col-sm-6">
                                                <label>Display Shelf Life<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="display_shelf_life" name="display_shelf_life" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Measurement Unit<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="measurement_unit" name="measurement_unit" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    @foreach($measurement_unit as $units)
                                                        <option value="{{$units->id}}">{{$units->unit_symbol}}</option>
                                                    @endforeach
                                                </select><br/><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Minimum Weight<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="min_weight" name="min_weight" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Maximum Weight<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="max_weight" name="max_weight" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade mt-2" id="product_details" role="tabpanel" aria-labelledby="product_details-tab">
                                        <div class="row">   
                                            <div class="col-sm-6">
                                                <label>Product<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="product" name="product" style="width: 100% !important;" onchange="getProductDetails(this.value)">
                                                    <option value="">Select</option>
                                                    @foreach($product as $products)
                                                        <option value="{{$products->id}}">{{$products->product_name}}</option>
                                                    @endforeach
                                                </select><br/><br/>
                                            </div> 
                                            <div class="col-sm-6">
                                                <label>Product Category<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="product_category" name="product_category" style="width: 100% !important;">
                                                    {{-- <option value="">Select</option>
                                                    @foreach($category as $categories)
                                                        <option value="{{$categories->id}}">{{$categories->category_name}}</option>
                                                    @endforeach --}}
                                                </select><br/><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Product Form<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="product_form" name="product_form" style="width: 100% !important;">
                                                    {{-- <option value="">Select</option>
                                                    @foreach($product_form as $forms)
                                                        <option value="{{$forms->id}}">{{$forms->product_form_name}}</option>
                                                    @endforeach --}}
                                                </select><br/><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Packaging Treatment<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="packaging_treatment" name="packaging_treatment" style="width: 100% !important;">
                                                    {{-- <option value="">Select</option>
                                                    @foreach($packaging_treatment as $treatments)
                                                        <option value="{{$treatments->id}}">{{$treatments->packaging_treatment_name}}</option>
                                                    @endforeach --}}
                                                </select><br/><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Packing Type<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="packing_type" name="packing_type" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    @foreach($packing_type as $types)
                                                        <option value="{{$types->id}}">{{$types->packing_name}}</option>
                                                    @endforeach
                                                </select><br/><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Packaging Machine<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="packaging_machine" name="packaging_machine" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    @foreach($packaging_machine as $machines)
                                                        <option value="{{$machines->id}}">{{$machines->packaging_machine_name}}</option>
                                                    @endforeach
                                                </select><br/><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Packaging Material<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="packaging_material" name="packaging_material" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    @foreach($packaging_material as $materials)
                                                        <option value="{{$materials->id}}">{{$materials->packaging_material_name}}</option>
                                                    @endforeach
                                                </select><br/><br/>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-success" onclick="submitForm('addPackagingSolution','post')">Submit</button>
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
<script>
    $('.select2').select2();
</script>