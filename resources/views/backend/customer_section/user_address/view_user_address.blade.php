<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View User Address Details</h5>
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
