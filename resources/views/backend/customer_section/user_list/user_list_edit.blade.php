<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit User List : {{ $data->name }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <form id="editUserList" method="post" action="saveUserList?id={{ $data->id }}">
                            <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Name<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="name" name="name" value="{{ $data->name }}"><br />
                                    </div>
                                    {{-- <div class="col-sm-6">
                                        <label>Email<span style="color:#ff0000">*</span></label>
                                        <input disabled class="form-control required" type="text" id="email" name="email" value="{{ $data->email }}"><br />
                                    </div> --}}
                                    <dl class="col-sm-6">
                                        <label>Email</label>
                                        <dd class="form-control" readonly>{{ $data->email; }}</dd>
                                    </dl>
                                    <div class="col-sm-6">
                                        <label>Phone Country Code<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="phone_country_code" name="phone_country_code" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($country as $code)
                                                @if ($code->id == $data->phone_country_id)
                                                    <option value="{{ $code->id }}" selected>+{{ $code->phone_code }}</option>
                                                @else
                                                    <option value="{{ $code->id }}">+{{ $code->phone_code }}</option>
                                                @endif
                                            @endforeach
                                        </select><br><br>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Phone Number<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="phone" name="phone" value="{{ $data->phone }}" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Whatsapp Country Code</label>
                                        <select class="select2" id="whatsapp_country_code" name="whatsapp_country_code" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($country as $code)
                                                @if ($code->id == $data->whatsapp_country_id)
                                                    <option value="{{ $code->id }}" selected>+{{ $code->phone_code }}</option>
                                                @else
                                                    <option value="{{ $code->id }}">+{{ $code->phone_code }}</option>
                                                @endif
                                            @endforeach
                                        </select><br><br>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Whatapp Number</label>
                                        <input class="form-control" type="text" id="whatsapp_no" name="whatsapp_no" value="{{ $data->whatsapp_no }}" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Currency</label>
                                        <select class="select2" id="currency" name="currency" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($currency as $val)
                                                @if ($val->id == $data->currency_id)
                                                    <option value="{{ $val->id }}" selected>{{ $val->currency_code }}</option>
                                                @else
                                                    <option value="{{ $val->id }}">{{ $val->currency_code }}</option>
                                                @endif
                                            @endforeach
                                        </select><br><br>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('editUserList','post')">Update</button>
                                            <a href="{{URL::previous()}}" class="btn btn-danger px-3 py-1"></i>Cancel</a>
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
</section>
<script>
$('.select2').select2();
function checkFiles(files) {
    if (files.length > 5) {
        $('#attachment').val('');
        bootbox.alert("Length exceeded, maximum allowed files are 5");
    }
}
</script>
