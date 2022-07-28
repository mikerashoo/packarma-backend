<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Vendor Approval Status : {{ $data->vendor_name }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                            <div class="card-text">   
                            <h5 class="mb-2 text-bold-500"><i class="ft-link mr-2"></i>Approval Details</h5> 
                            <br>
                                <div class="col-md-12 row">
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-4 text-left">Vendor Name :</dt>
                                            <dd class="col-sm-8">{{ $data->vendor_name }}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-4 text-left">Vendor Email :</dt>
                                            <dd class="col-sm-8">{{ $data->vendor_email }}</dd>
                                        </dl>                                                    
                                    </div>                                       
                                </div>                                    
                            </div>
                    		<form id="vendorApprovalForm" method="post" action="saveVendorApprovalData?id={{$data->id}}">                                
                    			@csrf
                        		<div class="row">
                        			<div class="col-sm-6">
                        				<label>Approval Status<span style="color:#ff0000">*</span></label>
                        				<select class="select2 required" id="approval_status" name="approval_status" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach($approvalArray as $key => $val)
                                                @if($key == $data->approval_status)
                                                    <option value="{{$key}}" selected>{{$val}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$val}}</option>
                                                @endif
                                            @endforeach
                                        </select><br/>
                        			</div>
                                    <div class="col-sm-6" id="gstin_div">
                        				<label>GST Number<span style="color:#ff0000">*</span></label>
                        				<input class="form-control" type="text" id="gstin" name="gstin" value="{{$data->gstin}}"><br/>
                        			</div>   
                                    <div class="col-sm-6" id="gst_certificate_div">
                                        <label>GST Certificate<span style="color:#ff0000">*</span></label><br>
                                        @if (!empty($data->gst_certificate))
                                            @if(str_contains($data->gst_certificate, '.pdf'))
                                                <span><i class="fa fa-edit"></i>{{$data->gst_certificate}}</span>
                                            @else
                                                <img src="{{ $data->image_path}}" width="200px" height="auto">
                                            @endif
                                        @else
                                            <p style="color:blue;">Note : Upload file size <?php echo  config('global.DIMENTIONS.GSTCERTIFICATE'); ?></p>
                                            <input class="form-control" type="file" id="gst_certificate" name="gst_certificate" accept="image/png, image/jpg, image/jpeg, application/pdf" onchange="checkFiles(this.files)"><br/>
                                        @endif
                                    </div>
                                    <div class="col-sm-6" id="remark">
                        				<label>Remark</label>
                        				<textarea class="form-control" id="admin_remark" name="admin_remark">{{$data->admin_remark}}</textarea><br/>
                        			</div>                     		
                        		</div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="button" class="btn btn-success" onclick="submitForm('vendorApprovalForm','post')">Update</button>
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