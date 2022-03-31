<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Staff User</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <!-- <hr class="mb-0"> -->
                    	<div class="card-body">
                    		<form id="saveStaffData" method="post" action="saveStaff">
								<h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                        			<div class="col-sm-6">
                        				<label>Role</label>
                        				<select class="select2" id="role_id" name="role_id" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($data as $roles)
                                                <option value="{{$roles->id}}">{{$roles->role_name}}</option>
                                            @endforeach
                                        </select><br/>
                        			</div>
                        			<div class="col-sm-6">
                        				<label>Name</label>
                        				<input class="form-control" type="text" id="name" name="name"><br/>
                        			</div>
                        			<div class="col-sm-6">
                        				<label>Email ID</label>
                        				<input class="form-control" type="email" id="email" name="email"><br/>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Phone</label>
                                        <input class="form-control" type="text" id="phone" name="phone"><br/>
                                    </div>
                        			<div class="col-sm-6">
                        				<label>Password</label>
                        				<input class="form-control" type="password" id="password" name="password"><br/>
                        			</div>
                        			<div class="col-sm-6">
                        				<label>Address</label>
                        				<textarea class="form-control" id="address" name="address"></textarea><br/>
                        			</div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('saveStaffData','post')">Submit</button>
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