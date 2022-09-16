<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Packaging Machine</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="addPackagingMachine" method="post" action="savePackagingMachine">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Packaging Machine Name<span style="color:#ff0000">*</span></label>
                        				<input class="form-control required" type="text" id="packaging_machine_name" name="packaging_machine_name"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Packaging Machine Description</label>
                        				<input class="form-control" type="text" id="packaging_machine_description" name="packaging_machine_description"><br/>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Packaging Machine Image</label>
                                        <p style="color:blue;">Note : Upload file size <?php echo  config('global.DIMENTIONS.PACKAGING_MACHINE'); ?></p>
                                        <input type="file" id="packaging_machine_image" name="packaging_machine_image" class="form-control" accept="image/png, image/jpg, image/jpeg"><br/>
                                    </div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addPackagingMachine','post')">Submit</button>
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