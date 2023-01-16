<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Currency : {{ $data['data']->currency_name }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i
                                            class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                       
                        <!-- <hr class="mb-0"> -->
                        <div class="card-body">
                            <form id="editCurrencyForm" method="post" action="saveCurrency?id={{ $data['data']->id }}">
                                <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                        				<label>Currency Name<span class="text-danger">*</span></label>
                        				<input class="form-control required" type="text" value="{{ $data['data']->currency_name }}" id="currency_name" name="currency_name" style="text-transform: capitalize;"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Currency Symbol<span class="text-danger">*</span></label>
                        				<input class="form-control required" type="text" value="{{ $data['data']->currency_symbol }}" id="currency_symbol" name="currency_symbol" style="text-transform: uppercase;"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Currency Code<span class="text-danger">*</span></label>
                        				<input class="form-control required" type="text" value="{{ $data['data']->currency_code }}" id="currency_code" name="currency_code" style="text-transform: uppercase;}"><br/>
                        			</div>
                                    <div class="col-sm-6">
                        				<label>Exchange Rate<span class="text-danger">*</span></label>
                        				<input class="form-control required" type="text" value="{{ $data['data']->exchange_rate }}" step=".001" id="exchange_rate" name="exchange_rate" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br/>
                        			</div> 
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('editCurrencyForm','post')">Submit</button>
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
