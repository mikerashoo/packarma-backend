<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit User Address : {{ $data->user->name }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="editUserAddressForm" method="post" action="saveUserAddress?id={{ $data->id }}">
                                <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane fade mt-2 show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>User<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="user" name="user" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    @foreach ($user as $users)
                                                        @if ($users->id == $data->user_id)
                                                            <option value="{{ $users->id }}" selected>{{ $users->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select><br/><br>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Address Name</label>
                                                <input class="form-control" type="text" id="address_name" name="address_name" value="{{ $data->address_name }}"><br />
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Address Type</label>
                                                <select class="select2" id="address_type" name="address_type" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    @foreach($addressType as $key => $val)
                                                        @if ($key == $data->type)
                                                            <option value="{{$key}}" selected>{{$val}}</option>
                                                        @else
                                                            <option value="{{$key}}">{{$val}}</option>
                                                        @endif
                                                    @endforeach
                                                </select><br/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Mobile Number</label>
                                                <input class="form-control" type="text" id="mobile_no" name="mobile_no" value="{{ $data->mobile_no }}" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br />
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Country<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="country" name="country" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    @foreach ($country as $countries)
                                                        @if ($countries->id == $data->country_id)
                                                            <option value="{{ $countries->id }}" selected>{{ $countries->country_name }}</option>
                                                        @else
                                                            <option value="{{ $countries->id }}">{{ $countries->country_name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select><br />
                                            </div>
                                            <div class="col-sm-6">
                                                <label>State<span style="color:#ff0000">*</span></label>
                                                <select class="select2 required" id="state" name="state" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    @foreach ($state as $states)
                                                        @if ($states->id == $data->state_id)
                                                            <option value="{{ $states->id }}" selected>{{ $states->state_name }}</option>
                                                        @else
                                                            <option value="{{ $states->id }}">{{ $states->state_name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select><br><br>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>City Name<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="city_name" name="city_name" value="{{ $data->city_name }}"><br />
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Pincode<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="pincode" name="pincode" value="{{ $data->pincode }}" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br />
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Flat<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="flat" name="flat" value="{{ $data->flat }}"><br />
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Area<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="area" name="area" value="{{ $data->area }}"><br />
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Landmark<span style="color:#ff0000">*</span></label>
                                                <input class="form-control required" type="text" id="landmark" name="landmark" value="{{ $data->land_mark }}"><br />
                                            </div>
                                            <div class="col-sm-6" id="gst_no_input">
                                                <label>GST Number<span style="color:#ff0000">*</span></label>
                                                <input class="form-control" type="text" id="gst_no" name="gst_no" value="{{ $data->gstin }}"><br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-success" onclick="submitForm('editUserAddressForm','post')">Update</button>
                                                <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i>Back</a>
                                            </div>
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
