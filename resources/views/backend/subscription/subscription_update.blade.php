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
                                                        <dt class="col-md-8 text-left">Subscription Type :</dt>
                                                        <dd class="col-md-4">{{ subscriptionType($data->subscription_type); }}</dd>
                                                    </dl>
                                                </div>
                                                <div class="col-md-6">
                                                    <dl class="row">
                                                        <dt class="col-md-8 text-left">Subscription Amount:</dt>
                                                        <dd class="col-md-4">{{ $data->amount }} </dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Subscription Amount<span style="color:#ff0000">*</span></label>
                        				<input class="form-control" type="text" step=".001" id="amount" name="amount" value="{{ $data->amount }}" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46' <?php echo ($data->subscription_type == 'free' ? 'disabled' : '')?>><br/>
                        			</div>

                                    <div class="col-sm-6">
                        				<label>Credit Amount<span style="color:#ff0000">*</span></label>
                        				<input class="form-control" type="text" min="0" step=".001" id="credit_amount" name="credit_amount" value="{{ $data->credit_amount }}"  ><br/>
                        			</div>
                        		</div>
                                <?php if($data->subscription_type =='free'){ ?>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Duration(in days)<span style="color:#ff0000">*</span></label>
                                            <input class="form-control required" type="number" id="duration" name="duration" value="{{ $data->duration }}">
                                            <br/>
                                        </div>
                                    </div>
                                <?php } ?>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('editSubscriptionData','post')">Update</button>
                                            <a href="{{URL::previous()}}" class="btn btn-danger px-3 py-1"></i> Cancel</a>
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
