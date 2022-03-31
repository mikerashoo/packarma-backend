<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit City :  {{$data['data']->city_name}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <!-- <hr class="mb-0"> -->
                    	<div class="card-body">
                    		<form id="editCityForm" method="post" action="saveCity?id={{$data['data']->id}}">
                                <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                        			<div class="col-sm-6">
                        				<label>City Name</label>
                        				<input class="form-control" type="text" id="city_name" name="city_name" value="{{$data['data']->city_name}}"><br/>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>State Name</label>
                                        <select class="select2" id="state_id" name="state_id" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($data['state'] as $state)
                                                @if ($state->id == $data['data']->state_id)
                                                    <option value="{{ $state->id }}" selected>{{ $state->state_name }}</option>
                                                @else
                                                    <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br />
                                    </div>
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('editCityForm','post')">Update</button>
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