<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add User Address</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i>Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="addUserAddressForm" method="post" action="saveUserAddress">
                                <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>User<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="user" name="user" style="width: 100% !important;">
                                            @if (!isset($id))
                                                <option value="">Select</option>
                                            @endif
                                            @foreach ($user as $users)
                                            @if (isset($id))
                                                @if ($users->id == $id)
                                                    <option selected value="{{ $users->id }}">{{ $users->name }}</option>
                                                @endif
                                            @else
                                                <option value="{{ $users->id }}">{{ $users->name }}</option>
                                            @endif
                                        @endforeach
                                        </select><br><br>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Address Name<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="address_name" name="address_name"><br />
                                    </div>
                                    <div class="col-sm-6">
                        				<label>Address Type<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="type" name="type" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($addressType as $key => $val)
                                                <option value="{{$key}}">{{$val}}</option>
                                            @endforeach
                                        </select><br/>
                        			</div>
                                    <div class="col-sm-6">
                                        <label>Mobile Number<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="mobile_no" name="mobile_no" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Country<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="country" name="country" style="width: 100% !important;" >
                                            <option value="">Select</option>
                                            @foreach ($country as $username)
                                                <option value="{{ $username->id }}">{{ $username->country_name }}</option>
                                            @endforeach
                                        </select><br><br>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>State<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="state" name="state" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($state as $statename)
                                                <option value="{{ $statename->id }}">{{ $statename->state_name }}
                                                </option>
                                            @endforeach
                                        </select><br><br>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>City Name<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="city_name" name="city_name"><br>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Pincode<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="pincode" name="pincode" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Flat<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="flat" name="flat"><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Area<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="area" name="area"><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Landmark<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="landmark" name="landmark"><br />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('addUserAddressForm','post')">Submit</button>
                                            <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $('.select2').select2();
</script>
