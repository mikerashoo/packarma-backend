<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="card-content">
                    	<div class="card-body">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" data-url="review_view">
                                        <tr>
                                            <td><strong>Vendor Name : </strong></td>
                                            <td>{{ $data->vendor_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email : </strong></td>
                                            <td>{{ $data->vendor_email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone : </strong></td>
                                            <td><span>+{{ $data['phone_country']->phone_code }}</span><span> {{ $data->phone }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>GST Number : </strong></td>
                                            <td>{{ $data->gstin ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>GST Certificate : </strong></td>
                                            <td>
                                                @if (!empty($data->gst_certificate))
                                                    @if (str_contains($data->gst_certificate, '.pdf'))
                                                        <a href="{{ListingImageUrl('vendor_gst_certificate',$data->gst_certificate)}}" target="_blank"><i class="fa fa-file"></i>  {{ $data->gst_certificate}}</a>
                                                    @else
                                                        <a href="{{ListingImageUrl('vendor_gst_certificate',$data->gst_certificate)}}" target="_blank"><img src="{{ListingImageUrl('vendor_gst_certificate',$data->gst_certificate)}}" width="150px" height="auto"/></a>
                                                    @endif
                                                @else
                                                {{'-'}}
                                                @endif

                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Approval Status : </strong></td>
                                            <td>{{ approvalStatusArray($data->approval_status) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date Time</strong></td>
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
</section>