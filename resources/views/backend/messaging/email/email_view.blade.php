<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Pachaging Treatment : {{$data->packaging_treatment_name}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <td><strong>Email Title</strong></td>
                                            <td>{{$data->title}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email Subject</strong></td>
                                            <td>{{$data->subject}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email Description</strong></td>
                                            <td>{{$data->content}}</td>
                                        </tr>
                                        <tr>
                                            <td class="col-sm-5"><strong>User Type</strong></td>
                                            <td>{{messageUserType($data->user_type)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Trigger</strong></td>
                                            <td>{{messageTrigger($data->trigger)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>{{displayStatus($data->status)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date Time</strong></td>
                                            <td>{{date('d-m-Y h:i A', strtotime($data->updated_at)) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>