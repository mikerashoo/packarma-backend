<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View User Details</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <h5 class="mb-2 text-bold-500"><i class="ft-info mr-2"></i>User Details</h5>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" data-url="user_view">
                                            <tr>
                                                <td class="col-sm-5"><strong>Name</strong></td>
                                                <td>{{ $data->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email</strong></td>
                                                <td>{{ $data->email }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Phone</strong></td>
                                                <td><span>+{{ $data['phone_country']->phone_code }}</span><span> {{ $data->phone }}</span></td>
                                            </tr>
                                            @if ($data->whatsapp_country_id != 0 && !empty($data->whatsapp_no))
                                                <tr>
                                                    <td><strong>Whatapp Number</strong></td>
                                                    <td><span>+{{ $data['whatsapp_country']->phone_code }}</span><span> {{ $data->whatsapp_no }}</span></td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td><strong>GST Number</strong></td>
                                                @if (!empty($data->gstin))
                                                    <td>{{ $data->gstin }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td><strong>Approval Status</strong></td>
                                                <td>{{ approvalStatusArray($data->approval_status) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Date Time</strong></td>
                                                <td>{{date('d-m-Y H:i A', strtotime($data->updated_at)) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <h5 class="mb-2 text-bold-500"><i class="ft-link mr-2"></i>Address Details</h5>
                                <div class="col-12 col-xl-12 users-module">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>State</th>
                                                    <th>City</th>
                                                    <th>Pincode</th>
                                                    <th>Area</th>
                                                    <th>Flat</th>
                                                    <th>Landmark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($userAddress as $value)
                                                    <tr>
                                                        <td>{{ $value->state->state_name }}</td>
                                                        <td>{{ $value->city_name }}</td>
                                                        <td>{{ $value->pincode }}</td>
                                                        <td>{{ $value->area }}</td>
                                                        <td>{{ $value->flat }}</td>
                                                        <td>{{ $value->land_mark }}</td>
                                                    </tr>
                                                @endforeach
                                                @if (empty($userAddress->toArray()))
                                                    <tr>
                                                        <td colspan="6" class="text-center">No address found</td>
                                                    </tr>
                                                @endif
                                            </tbody>
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
