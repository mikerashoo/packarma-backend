<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Update Subscription</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="editSubscriptionData" method="post" action="subscriptionUpdate?id={{$data->id}}">                                
                                    <div class="card-text">                                        
                                        <div class="card-text">
                                            <div class="col-md-12 row">
                                                <div class="col-md-6">
                                                    <dl class="row">
                                                        <dt class="col-sm-4 text-left">Type :</dt>
                                                        <dd class="col-sm-8">{{ subscriptionType($data->type); }}</dd>
                                                    </dl>
                                                    <dl class="row">                                                                        
                                                        <dt class="col-sm-4 text-left">Amount :</dt>
                                                        <dd class="col-sm-8">{{  $data->amount }} </dd>
                                                    </dl>                                                    
                                                </div>
                                            </div>                                    
                                        </div>
                                    </div>
                    			@csrf
                        		<div class="row">
                        			<div class="col-sm-6">
                        				<label>Type<span style="color:#ff0000">*</span></label>
                        				<select class="select2" id="type" name="type" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($type as $key => $val)
                                                @if($key == $data->type)
                                                    <option value="{{$key}}" selected>{{$val}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$val}}</option>
                                                @endif
                                            @endforeach
                                        </select><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Amount<span style="color:#ff0000">*</span></label>
                        				<input class="form-control" type="number" step=".001" id="amount" name="amount"><br/>
                        			</div>                        		
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('editSubscriptionData','post')">Update</button>
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