<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Approval Details</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-brodered" data-url="review_view">
                                            <tr>
                                                <td><strong>Name : </strong></td>
                                                <td>{{ $data->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email : </strong></td>
                                                <td>{{ $data->email }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Phone : </strong></td>
                                                <td><span>+{{ $data['phone_country']->phone_code }}</span><span> {{ $data->phone }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Whatapp Number : </strong></td>
                                                <td><span>+{{ $data['whatsapp_country']->phone_code }}</span><span> {{ $data->whatsapp_no }}</span></td>
                                            </tr>

                                            <tr>
                                                <td><strong>Approval Status : </strong></td>
                                                <td>{{ approvalStatusArray($data->approval_status) }}</td>
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
    </div>
</section>
