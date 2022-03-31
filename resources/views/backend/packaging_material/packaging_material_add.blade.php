<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Packaging Material</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="addPackagingMaterial" method="post" action="savePackagingMaterial">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Packaging Material Name<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="packaging_material_name" name="packaging_material_name"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Material Description<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="material_description" name="material_description"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Shelf Life<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="shelf_life" name="shelf_life"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Price<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="price" name="price" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addPackagingMaterial','post')">Submit</button>
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