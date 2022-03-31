<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Upadate User Approval Status</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
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
                                            <dt class="col-sm-4 text-left">Name : </dt>
                                            <dd class="col-sm-8">{{ $data->name }}</dd>
                                        </dl>
                                        <dl class="row">
                                            <dt class="col-sm-4 text-left">Email : </dt>
                                            <dd class="col-sm-8">{{ $data->email }} </dd>
                                        </dl>
                                        <dl class="row">
                                            <dt class="col-sm-4 text-left">Approval Status:</dt>
                                            <dd class="col-sm-8">{{ approvalStatusArray($data->approval_status) }} </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-4 text-left">Phone Number : </dt>
                                            <dd class="col-sm-8"><span>+{{ $data['phone_country']->phone_code }}</span><span> {{ $data->phone }}</span></dd>
                                        </dl>
                                        <dl class="row">
                                            <dt class="col-sm-4 text-left">Whatsapp Number : </dt>
                                            <dd class="col-sm-8"><span>+{{ $data['whatsapp_country']->phone_code }}</span><span> {{ $data->whatsapp_no }}</span></dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <form id="updateApprovalForm" method="post" action="saveApprovalStatus?id={{ $data->id }}">
                                @csrf
                                <div class="col-sm-12 row">
                                    <div class="col-sm-6">
                                        <label>Approval Status<span style="color:#ff0000">*</span></label>
                                        <select class="select2 required" id="approval_status" name="approval_status" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($approvalArray as $key => $val)
                                                @if ($key == $data->approval_status)
                                                    <option value="{{ $key }}" selected>{{ $val }}</option>
                                                @else
                                                    <option value="{{ $key }}">{{ $val }}</option>
                                                @endif
                                            @endforeach
                                        </select><br />
                                    </div>
                                    <div class="col-sm-6" id="remark">
                                        <label>Remark<span style="color:#ff0000">*</span></label>
                                        <textarea class="form-control" id="admin_remark" name="admin_remark">{{ $data->admin_remark }}</textarea><br />
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('updateApprovalForm','post')">Update</button>
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
