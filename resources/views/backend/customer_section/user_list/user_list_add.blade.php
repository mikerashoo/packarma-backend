<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add User</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <form id="addUserListForm" method="post" action="saveUserList">
                            <h4 class="form-section"><i class="ft-info"></i>Details</h4>
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Name<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="name" name="name"><br/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Email<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="email" name="email"><br/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Phone Country Code<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="phone_country_code" name="phone_country_code" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($country as $code)
                                                <option value="{{ $code->id }}">+{{ $code->phone_code }}</option>
                                            @endforeach
                                        </select><br><br>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Phone Number<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="phone" name="phone" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Whatsapp Country Code<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="whatsapp_country_code" name="whatsapp_country_code" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($country as $code)
                                                <option value="{{ $code->id }}">+{{ $code->phone_code }}</option>
                                            @endforeach
                                        </select><br><br>
                                    </div> 
                                    <div class="col-sm-6">
                                        <label>Whatapp Number<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="text" id="whatsapp_no" name="whatsapp_no" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'>
                                    </div> 
                                    <div class="col-sm-6">
                                        <label>Currency<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="currency" name="currency" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($currency as $val)
                                                <option value="{{ $val->id }}">{{ $val->currency_code }}</option>
                                            @endforeach
                                        </select><br><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('addUserListForm','post')">Submit</button>
                                                <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a><br> <br>
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
        bootbox.alert("length exceeded; maximum allowed files are 5");
    }
}
</script>
