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
                                                <td><span>+{{ $data['phone_country']->phone_code }}</span><span> {{ $data->phone }}</span></td>
                                            </tr>
                                            @if ($data->whatsapp_country_id != 0 && !empty($data->whatsapp_no))
                                                <tr>
                                                    <td><strong>Whatapp Number : </strong></td>
                                                    <td><span>+{{ $data['whatsapp_country']->phone_code }}</span><span> {{ $data->whatsapp_no }}</span></td>
                                                </tr> 
                                            @endif
                                            <tr>
                                                <td><strong>Approval Status : </strong></td>
                                                <td>{{ approvalStatusArray($data->approval_status) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>GST Number</strong></td>
                                                @if (!empty($data->gstin))
                                                    <td>{{ $data->gstin }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td><strong>GST Certificate</strong></td>
                                                @if (!empty($data->gst_certificate))
                                                    @if (str_contains($data->gst_certificate, '.pdf'))
                                                        <td><a href="{{ListingImageUrl('gst_certificate',$data->gst_certificate)}}" target="_blank"><i class="fa fa-file"></i>  {{ $data->gst_certificate}}</a></td>
                                                    @else
                                                        <td><a href="{{ListingImageUrl('gst_certificate',$data->gst_certificate)}}" target="_blank"><img src="{{ListingImageUrl('gst_certificate',$data->gst_certificate)}}" width="150px" height="auto"/></a></td>
                                                    @endif
                                                @else
                                                    <td>-</td>
                                                @endif
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
