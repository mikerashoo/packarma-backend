<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Packaging Treatment</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="addPackagingTreatment" method="post" action="savePackagingTreatment">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Packaging Treatment Name<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="packaging_treatment_name" name="packaging_treatment_name"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Packaging Treatment Description</label>
                        				<input class="form-control required" type="text" id="packaging_treatment_description" name="packaging_treatment_description"><br/>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Packaging Treatment Image</label>
                                        <p style="color:blue;">Note : Upload file size <?php echo  config('global.DIMENTIONS.PACKAGING_TREATMENT'); ?></p>
                                        <input type="file" id="packaging_treatment_image" name="packaging_treatment_image" class="form-control" accept="packaging_treatment_image/png, packaging_treatment_image/jpg, packaging_treatment_image/jpeg" onchange="checkFiles(this.files)"><br/>
                                    </div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addPackagingTreatment','post')">Submit</button>
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