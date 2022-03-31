<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Currency</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>                        
                    	<div class="card-body">
                    		<form id="addCurrencyForm" method="post" action="saveCurrency">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                    			@csrf
                        		<div class="row">
                                    <div class="col-sm-6">
                        				<label>Currency Name</label>
                        				<input class="form-control" type="text" id="currency_name" name="currency_name" style="text-transform: capitalize;"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Currency Symbol</label>
                        				<input class="form-control" type="text" id="currency_symbol" name="currency_symbol" style="text-transform: uppercase;"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Currency Code</label>
                        				<input class="form-control" type="text" id="currency_code" name="currency_code" style="text-transform: uppercase;"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Exchange Rate</label>
                        				<input class="form-control" type="number" step=".001" id="exchange_rate" name="exchange_rate"><br/>
                        			</div>                                    
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('addCurrencyForm','post')">Submit</button>
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