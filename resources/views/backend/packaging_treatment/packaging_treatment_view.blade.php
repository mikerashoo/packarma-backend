<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Packaging Treatment Details</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
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
