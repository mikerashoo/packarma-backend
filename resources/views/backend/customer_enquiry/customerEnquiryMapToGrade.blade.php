<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Map Grade To Customer Enquiry : {{ $data->id }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="customerGradeMapForm" method="post" action="saveCustomerGradeMap?id={{ $data->id }}">
                                <div class="card-text">
                                    <div class="col-md-12 row">
                                        <div class="col-md-6">
                                            <dl class="row">                                                                        
                                                <dt class="col-sm-4 text-left">Description :</dt>
                                                <dd class="col-sm-8">{{ $data->description }} </dd>
                                            </dl>
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">User Name :</dt>
                                                <dd class="col-sm-8">{{ $data['user']->name }}</dd>
                                            </dl>
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">Enquiry Type :</dt>
                                                <dd class="col-sm-8">{{ customerEnquiryType($data->enquiry_type); }}</dd>
                                            </dl>
                                        </div>
                                        <div class="col-md-6">
                                            <dl class="row">                                                                        
                                                <dt class="col-sm-4 text-left">Address :</dt>
                                                <dd class="col-sm-8">{{ $data->address }} </dd>
                                            </dl>
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">City Name :</dt>
                                                <dd class="col-sm-8">{{ $data['city']->city_name }}</dd>
                                            </dl>
                                            <dl class="row">
                                                <dt class="col-sm-4 text-left">State Name :</dt>
                                                <dd class="col-sm-8">{{ $data['state']->state_name; }}</dd>
                                            </dl>
                                        </div>                                       
                                    </div>                                    
                                </div>                                
                                @csrf                            
                                <div class="row">                                                                    
                                    <div class="col-sm-6">
                        				<label>Grade Name<span style="color:#ff0000">*</span></label>
                        				<select class="form-control select2" id="grade" name="grade" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($grade as $grades)
                                                @if ($grades->id == $data->grade_id)
                                                    <option value="{{ $grades->id }}" selected>{{ $grades->grade_name }}</option>
                                                @else
                                                    <option value="{{ $grades->id }}">{{ $grades->grade_name }}</option>
                                                @endif
                                            @endforeach
                                        </select><br />
                        			</div>                                    
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('customerGradeMapForm','post')">Submit</button>
                                            <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
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
