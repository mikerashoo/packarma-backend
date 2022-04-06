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
                                        <table class="table table-striped table-bordered" data-url="user_view">
                                            <tr>
                                                <td class="col-sm-5"><strong>User</strong></td>
                                                <td>{{ $data->user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Country</strong></td>
                                                <td>{{ $data->country->country_name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>State</strong></td>
                                                <td>{{ $data->state->state_name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>City</strong></td>
                                                <td>{{ $data->city->city_name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Address</strong></td>
                                                <td>{{ $data->address }}</td>

                                            </tr>
                                            <tr>
                                                <td><strong>Pincode</strong></td>
                                                <td>{{ $data->pincode }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Date Time</strong></td>
                                                <td>{{date('d-m-Y H:i A', strtotime($data->updated_at)) }}</td>
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
