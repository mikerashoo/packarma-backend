<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Recommendation Engine</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="addRecommendationEngine" method="post" action="saveRecommendationEngine">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Engine Name<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="engine_name" name="engine_name"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Structure Type<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="structure_type" name="structure_type"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Product<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="product" name="product" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($product as $products)
                                                <option value="{{$products->id}}">{{$products->product_name}}</option>
                                            @endforeach
                                        </select><br/><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Minimum Shelf Life<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="min_shelf_life" name="min_shelf_life" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Maximum Shelf Life<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="max_shelf_life" name="max_shelf_life" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Minimum Weight<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="min_weight" name="min_weight" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Maximum Weight<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="max_weight" name="max_weight" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Price<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="price" name="price" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Product Category<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="product_category" name="product_category" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($category as $categories)
                                                <option value="{{$categories->id}}">{{$categories->category_name}}</option>
                                            @endforeach
                                        </select><br/><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Product Form<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="product_form" name="product_form" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($product_form as $forms)
                                                <option value="{{$forms->id}}">{{$forms->product_form_name}}</option>
                                            @endforeach
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
                        				<label>Packaging Treatment<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="packaging_treatment" name="packaging_treatment" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($packaging_treatment as $treatments)
                                                <option value="{{$treatments->id}}">{{$treatments->packaging_treatment_name}}</option>
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
                                    <div class="col-sm-6">
                        				<label>Vendor<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="vendor" name="vendor" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($vendor as $vendors)
                                                <option value="{{$vendors->id}}">{{$vendors->vendor_name}}</option>
                                            @endforeach
                                        </select><br/><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>WVTR<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="wvtr" name="wvtr"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>OTR<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="otr" name="otr"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>COF<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="cof" name="cof"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>SIT<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="sit" name="sit"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>GSM<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="gsm" name="gsm"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Special Feature<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="special_feature" name="special_feature"><br/>
                        			</div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addRecommendationEngine','post')">Submit</button>
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