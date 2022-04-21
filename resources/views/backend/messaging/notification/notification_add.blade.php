<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Notification</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="addNotificationForm" method="post" action="saveNotification">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-12">
                        				<label>Title<span class="text-danger">*</span></label>
                        				<input class="form-control required" type="text" id="title" name="title"><br/>
                        			</div>
                                    <div class="col-sm-12">
                                        <label>Notification Body<span style="color:#ff0000">*</span></label>
                                        <textarea class="form-control required" id="notification_body" name="notification_body" ></textarea><br>
                                    </div>
                                    <div class="col-sm-6">
                        				<label>User Type<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="user_type" name="user_type" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($messageUserType as $key => $val)
                                                <option value="{{$key}}">{{$val}}</option>
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Trigger<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="trigger" name="trigger" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($messageTrigger as $key => $val)
                                                <option value="{{$key}}">{{$val}}</option>
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Notification Image</label>
                                        <p style="color:blue;">Note : Upload file size <?php echo  config('global.DIMENTIONS.NOTIFICATION'); ?></p>
                                        <input type="file" id="notification_image" name="notification_image" class="form-control" accept="notification_image/png, notification_image/jpg, notification_image/jpeg" onchange="checkFiles(this.files)"><br/>
                                    </div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addNotificationForm','post')">Submit</button>
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