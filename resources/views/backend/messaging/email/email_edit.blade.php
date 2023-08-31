<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Email : {{ $data->title }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>                        
                    	<div class="card-body">
                    		<form id="editEmailForm" method="post" action="saveEmail?id={{$data->id}}">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Email Title<span class="text-danger">*</span></label>
                        				<input class="form-control required" type="text" id="title" name="title" value="{{ $data->title }}"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Email Subject<span class="text-danger">*</span></label>
                        				<input class="form-control required" type="text" id="subject" name="subject" value="{{ $data->subject }}"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Email Label</label>
                        				<input class="form-control" type="text" id="label" name="label" value="{{ $data->label }}"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>User Type<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="user_type" name="user_type" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($messageUserType as $key => $val)
                                                @if($key == $data->user_type)
                                                    <option value="{{$key}}" selected>{{$val}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$val}}</option>
                                                @endif
                                            @endforeach
                                        </select><br/><br>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Trigger<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="trigger" name="trigger" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($messageTrigger as $key => $val)
                                                @if($key == $data->trigger)
                                                    <option value="{{$key}}" selected>{{$val}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$val}}</option>
                                                @endif
                                            @endforeach
                                        </select><br/><br>
                        			</div>
									<div class="col-sm-12">
                                        <label>Description<span style="color:#ff0000">*</span></label>
                                        <textarea class="form-control required" id="description" name="description" style="min-height: 400px;">{{ $data->content }}</textarea><br>
                                    </div> 
                        		</div>
                                <hr>
                                <div class="col-12 col-sm-7 mb-2">
                                    <h5 class="mb-1 text-bold-500"><i class="ft-info"></i> Replacement Options : </h5>
                                </div>
                                <div>
                                    <ul>
                                        <li class="mb-1">{first_name} - Replacement for user first name</li>
                                        <li class="mb-1">{logo} - Replacement for logo image</li>
                                        <li class="mb-1">{webinarid} - Replacement for webinar id</li>
                                        <li class="mb-1">{webinar_name} - Replacement for webinar name</li>
                                        <li class="mb-1">{webinar_description} - Replacement for webinar description</li>
                                        <li class="mb-1">{webinar_schedule} - Replacement for webinar schedule</li>
                                        <li class="mb-1">{webinar_time} - Replacement for webinar time</li>
                                        <li class="mb-1">{webinar_join_url} - Replacement for webinar join url</li>
                                        <li class="mb-1">{event_name} - Replacement for event name</li>
                                        <li class="mb-1">{event_description} - Replacement for event description</li>
                                        <li class="mb-1">{event_schedule} - Replacement for event schedule</li>
                                        <li class="mb-1">{event_time} - Replacement for event time</li>
                                        <li class="mb-1">{eventid} - Replacement for eventid</li>
                                        <li class="mb-1">{newsid} - Replacement for news id</li>
                                        <li class="mb-1">{blogid} - Replacement for blog id</li>
                                    </ul>
                                </div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitEditor('editEmailForm')">Submit</button>
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
<script>
    $('.select2').select2();
</script>
<script src="../public/backend/vendors/ckeditor5/ckeditor.js"></script>
<script>
    $( document ).ready(function() {
        var editor = loadCKEditor('description');
    });
</script>