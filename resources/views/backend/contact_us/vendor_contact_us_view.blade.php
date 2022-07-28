<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" data-url="review_view">
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
                                                <td>{{ $data->mobile }}</span></td>
                                            </tr>
                                             <tr>
                                                <td><strong>Subject : </strong></td>
                                                <td>{{ $data->subject }}</span></td>
                                            </tr>
                                             <tr>
                                                <td><strong>Details : </strong></td>
                                                <td>{{ $data->details }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Created Date Time</strong></td>
                                                <td>{{date('d-m-Y H:i A', strtotime($data->created_at)) }}</td>
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
