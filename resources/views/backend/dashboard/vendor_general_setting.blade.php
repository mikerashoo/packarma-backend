@extends('backend.layouts.app')
@section('content')
<div class="main-content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <section class="users-list-wrapper">
        	<div class="users-list-table">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                            <div class="card-header">
                                    <h4 class="card-title text-center">Manage Vendor General Setting</h4>
                                </div>
                                <!-- <hr class="mb-0"> -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mt-3">
                                            <!-- Nav tabs -->
                                            <ul class="nav flex-column nav-pills" id="myTab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="vendor_general-tab" data-toggle="tab" href="#vendor_general" role="tab" aria-controls="vendor_general" aria-selected="true">
                                                        <i class="ft-settings mr-1 align-middle"></i>
                                                        <span class="align-middle">General</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="vendor_about_us-tab" data-toggle="tab" href="#vendor_about_us" role="tab" aria-controls="vendor_about_us" aria-selected="false">
                                                        <i class="ft-info mr-1 align-middle"></i>
                                                        <span class="align-middle">About Us</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="vendor_terms-tab" data-toggle="tab" href="#vendor_terms" role="tab" aria-controls="vendor_terms" aria-selected="false">
                                                        <i class="ft-command mr-1 align-middle"></i>
                                                        <span class="align-middle">Terms and Condition</span>
                                                    </a>
                                                </li>
                                                
                                                <li class="nav-item">
                                                    <a class="nav-link" id="vendor_privacy-tab" data-toggle="tab" href="#vendor_privacy" role="tab" aria-controls="vendor_privacy" aria-selected="false">
                                                        <i class="ft-globe mr-1 align-middle"></i>
                                                        <span class="align-middle">Privacy Policy</span>
                                                    </a>
                                                </li>
                                              
                                                <li class="nav-item">
                                                    <a class="nav-link" id="vendor_notification-tab" data-toggle="tab" href="#vendor_notification" role="tab" aria-controls="vendor_notification" aria-selected="false">
                                                        <i class="ft-bell mr-1 align-middle"></i>
                                                        <span class="align-middle">Notification</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="vendor_app_link-tab" data-toggle="tab" href="#vendor_app_link" role="tab" aria-controls="vendor_app_link" aria-selected="false">
                                                        <i class="ft-link mr-1 align-middle"></i>
                                                        <span class="align-middle">App Link</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="vendor_app_version-tab" data-toggle="tab" href="#vendor_app_version" role="tab" aria-controls="vendor_app_version" aria-selected="false">
                                                        <i class="ft-play mr-1 align-middle"></i>
                                                        <span class="align-middle">App Version</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-9">
                                            <!-- Tab panes -->
                                            <div class="card">
                                                <div class="card-content">
                                                    <div class="card-body">
                                                        <div class="tab-content">
                                                            <!-- General Tab -->
                                                            <div class="tab-pane active" id="vendor_general" role="tabpanel" aria-labelledby="vendor_general-tab">
                                                                <form id="vendorGeneralForm" method="post" action="updateVendorSettingInfo?param=vendorGeneral">
                                                                @csrf
                                                                    <div class="row">
                                                                        <div class="col-12 form-group">
                                                                            <label for="vendor_system_email">System E-mail</label>
                                                                            <div class="controls">
                                                                                <input type="email" id="vendor_system_email" name="vendor_system_email" class="form-control" placeholder="E-mail" value="{{$data['vendor_system_email'] ?? ''}}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 form-group">
                                                                            <label for="vendor_meta_title">Meta Title</label>
                                                                            <div class="controls">
                                                                                <input type="text" id="vendor_meta_title" name="vendor_meta_title"  class="form-control" placeholder="" aria-invalid="false" value="{{$data['vendor_meta_title'] ?? ''}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 form-group">
                                                                            <label for="vendor_meta_keywords">Meta Keywords</label>
                                                                            <div class="controls">
                                                                                <input type="text" id="vendor_meta_keywords" name="vendor_meta_keywords" class="form-control" placeholder="" aria-invalid="false" value="{{$data['vendor_meta_keywords'] ?? ''}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 form-group">
                                                                            <label for="vendor_meta_description">Meta Description</label>
                                                                            <div class="controls">
                                                                                <input type="text" id="vendor_meta_description" name="vendor_meta_description" class="form-control" placeholder="" aria-invalid="false" value="{{$data['vendor_meta_description'] ?? ''}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                            <button type="button" class="btn btn-primary mr-sm-2 mb-1" onclick="submitForm('vendorGeneralForm','post')">Save Changes</button>
                                                                            <button type="reset" class="btn btn-secondary mb-1">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div class="tab-pane" id="vendor_about_us" role="tabpanel" aria-labelledby="vendor_about_us-tab">
                                                                <form id="vendorAboutusForm" method="post" action="updateVendorSettingInfo?param=vendorAboutus">
                                                                <!-- <form id="vendorAboutusForm" method="post" action="updateVendorSettingInfo/aboutus"> -->

                                                                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                                                    <div class="row">
                                                                        <div class="col-12 form-group">
                                                                            <label>About Us</label>
                                                                            <textarea class="ckeditor form-control" id="vendor_about_us_editor" name="vendor_about_us"> {{$data['vendor_about_us'] ?? ''}}</textarea>
                                                                        </div>
                                                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                            <button type="button" class="btn btn-primary mr-sm-2 mb-1" onclick="submitEditor('vendorAboutusForm')">Save Changes</button>
                                                                            <button type="reset" class="btn btn-secondary mb-1">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div class="tab-pane" id="vendor_terms" role="tabpanel" aria-labelledby="vendor_terms-tab">
                                                                <form id="vendorTncForm" method="post" action="updateVendorSettingInfo?param=vendorTnc">
                                                                @csrf
                                                                    <div class="row">
                                                                        <div class="col-12 form-group">
                                                                            <label>Terms and Condition</label>
                                                                            <textarea class="ckeditor form-control" id="vendor_terms_condition_editor" name="vendor_terms_condition">{{$data['vendor_terms_condition'] ?? ''}}</textarea>
                                                                        </div>
                                                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                            <button type="button" class="btn btn-primary mr-sm-2 mb-1" onclick="submitEditor('vendorTncForm')">Save Changes</button>
                                                                            <button type="reset" class="btn btn-secondary mb-1">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div class="tab-pane" id="vendor_privacy" role="tabpanel" aria-labelledby="vendor_privacy-tab">
                                                                <form id="vendorPrivacyForm" method="post" action="updateVendorSettingInfo?param=vendorPrivacy">
                                                                @csrf
                                                                    <div class="row">
                                                                        <div class="col-12 form-group">
                                                                            <label>Privacy Policy</label>
                                                                            <textarea class="ckeditor form-control" id="vendor_privacy_policy_editor" name="vendor_privacy_policy">{{$data['vendor_privacy_policy'] ?? ''}}</textarea>
                                                                        </div>
                                                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                            <button type="button" class="btn btn-primary mr-sm-2 mb-1" onclick="submitEditor('vendorPrivacyForm')">Save Changes</button>
                                                                            <button type="reset" class="btn btn-secondary mb-1">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                         

                                                            <div class="tab-pane" id="vendor_notification" role="tabpanel" aria-labelledby="vendor_notification-tab">
                                                                <div class="row">
                                                                    <h6 class="col-12 text-bold-400 pl-0">Notification</h6>
                                                                    <div class="col-12 mb-2">
                                                                            <input id="vendor_switchery1" type="checkbox" data-url="publishVendorEmailNotification"  data-id="trigger_vendor_email_notification" class="js-switch switchery" <?php echo ( isset($data['trigger_vendor_email_notification']) && $data['trigger_vendor_email_notification'] == 1) ? 'checked' : ''; ?> >
                                                                            <label for="vendor_switchery1">Trigger Email Notification</label>
                                                                    </div>
                                                                    <div class="col-12 mb-2">
                                                                            <input id="vendor_switchery2" type="checkbox" data-url="publishVendorWhatsappNotification"  data-id="trigger_vendor_whatsapp_notification" class="js-switch switchery" <?php echo (isset($data['trigger_vendor_whatsapp_notification']) && $data['trigger_vendor_whatsapp_notification'] == 1) ? 'checked' : ''; ?>>
                                                                            <label for="vendor_switchery2">Trigger Whatsapp Notification</label>
                                                                    </div>
                                                                    <div class="col-12 mb-2">
                                                                            <input id="vendor_switchery3" type="checkbox" data-url="publishVendorSMSNotification"  data-id="trigger_vendor_sms_notification" class="js-switch switchery" <?php echo (isset($data['trigger_vendor_sms_notification']) && $data['trigger_vendor_sms_notification'] == 1) ? 'checked' : ''; ?>>
                                                                            <label for="vendor_switchery3">Trigger SMS Notification</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- App Link Tab -->
                                                            <div class="tab-pane" id="vendor_app_link" role="tabpanel" aria-labelledby="vendor_app_link-tab">
                                                                <form id="vendorAppLinkForm" method="post" action="updateVendorSettingInfo?param=vendorAppLink">
                                                                @csrf
                                                                    <div class="row">
                                                                        <div class="col-12 form-group">
                                                                            <label for="vendor_android_url">Android</label> 
                                                                            <div class="controls">
                                                                                <input type="text" id="vendor_android_url" name="vendor_android_url" class="form-control" placeholder="" value="{{$data['vendor_android_url'] ?? ''}}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 form-group">
                                                                            <label for="vendor_ios_url">IOS</label>
                                                                            <div class="controls">
                                                                                <input type="text" id="vendor_ios_url" name="vendor_ios_url"  class="form-control" placeholder="" aria-invalid="false" value="{{$data['vendor_ios_url'] ?? ''}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                            <button type="button" class="btn btn-primary mr-sm-2 mb-1" onclick="submitForm('vendorAppLinkForm','post')">Save Changes</button>
                                                                            <button type="reset" class="btn btn-secondary mb-1">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                             <!-- App Version Tab -->
                                                            <div class="tab-pane" id="vendor_app_version" role="tabpanel" aria-labelledby="vendor_app_version-tab">
                                                                <form id="vendorAppVersionForm" method="post" action="updateVendorSettingInfo?param=vendorAppVersion">
                                                                @csrf
                                                                    <div class="row">
                                                                        <div class="col-12 form-group">
                                                                            <label for="vendor_android_version">Android (Format-> ["va1","val2","val3"])</label>
                                                                            <div class="controls">
                                                                                <input type="text" id="vendor_android_version" name="vendor_android_version" class="form-control" placeholder="" value="{{$data['vendor_android_version'] ?? ''}}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 form-group">
                                                                            <label for="vendor_ios_version">IOS (Format-> ["va1","val2","val3"])</label>
                                                                            <div class="controls">
                                                                                <input type="text" id="vendor_ios_version" name="vendor_ios_version"  class="form-control" placeholder="" aria-invalid="false" value="{{$data['vendor_ios_version'] ?? ''}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                            <button type="button" class="btn btn-primary mr-sm-2 mb-1" onclick="submitForm('vendorAppVersionForm','post')">Save Changes</button>
                                                                            <button type="reset" class="btn btn-secondary mb-1">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>   
                            	
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script src="../public/backend/vendors/ckeditor5/ckeditor.js"></script>
<script>
    $('#vendor_privacy-tab').on('click',function(){
        loadCKEditor('vendor_privacy_policy_editor');
    });
    $('#vendor_about_us-tab').on('click',function(){
        loadCKEditor('vendor_about_us_editor');
    });
    $('#vendor_terms-tab').on('click',function(){
        loadCKEditor('vendor_terms_condition_editor');
    });
</script>

@endsection

