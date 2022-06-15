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
                                            {{-- <tr>
                                                <td><strong>GST Information Number</strong></td>
                                                @if (!empty($data->gstin))
                                                    <td>{{ $data->gstin }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                            </tr> --}}
                                            <tr>
                                                <td><strong>Address Name</strong></td>
                                                @if (!empty($data->address_name))
                                                    <td>{{ $data->address_name }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td><strong>Address Type</strong></td>
                                                @if (!empty($data->type))
                                                    <td>{{ addressType($data->type) }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td><strong>Mobile Number</strong></td>
                                                @if (!empty($data->mobile_no))
                                                <td>+{{$data->country->phone_code.' '.$data->mobile_no}}</td>
                                            @else
                                                <td>-</td> 
                                            @endif
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
                                                <td>{{ $data->city_name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pincode</strong></td>
                                                <td>{{ $data->pincode }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Area</strong></td>
                                                <td>{{ $data->area }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Flat</strong></td>
                                                <td>{{ $data->flat }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Landmark</strong></td>
                                                <td>{{ $data->land_mark }}</td>
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
