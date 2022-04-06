<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="card-content">
                    	<div class="card-body">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <td class="col-sm-5"><strong>Packaging Treatment Name</strong></td>
                                            <td>{{$data->packaging_treatment_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Packaging Treatment Description</strong></td>
                                            <td>{{$data->packaging_treatment_description}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Packaging Treatment Status</strong></td>
                                            <td>{{displayStatus($data->status)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date Time</strong></td>
                                            <td>{{date('d-m-Y H:i A', strtotime($data->updated_at)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Packaging Tratment Image</strong></td>
                                            <td><img src="{{ListingImageUrl('packaging_treatment',$data->packaging_treatment_image)}}" width="150px" height="auto"/></td>
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
