<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Recommendation Engine Details</h5>
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
                                            <td class="col-sm-5"><strong>Recommendation Engine Name</strong></td>
                                            <td>{{$data->engine_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Structure Type</strong></td>
                                            <td>{{$data->structure_type}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Product Name</strong></td>
                                            <td>{{$data->product->product_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Minimum Shelf Life</strong></td>
                                            <td>{{$data->min_shelf_life}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Maximum Shelf Life</strong></td>
                                            <td>{{$data->max_shelf_life}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Display Shelf Life</strong></td>
                                            <td>{{$data->display_shelf_life}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Minimum Weight</strong></td>
                                            <td>{{$data->min_weight}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Maximum Weight</strong></td>
                                            <td>{{$data->max_weight}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Price</strong></td>
                                            <td>{{$data->approx_price}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Product Category</strong></td>
                                            <td>{{$data->category->category_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Product Form</strong></td>
                                            <td>{{$data->product_form->product_form_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Packing Type</strong></td>
                                            <td>{{$data->packing_type->packing_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Packaging Machine</strong></td>
                                            <td>{{$data->packaging_machine->packaging_machine_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Packaging Treatment</strong></td>
                                            <td>{{$data->packaging_treatment->packaging_treatment_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Packaging Material</strong></td>
                                            <td>{{$data->packaging_material->packaging_material_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Vendor Name</strong></td>
                                            <td>{{$data->vendor->vendor_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>WVTR</strong></td>
                                            <td>{{$data->wvtr}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>OTR</strong></td>
                                            <td>{{$data->otr}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>COF</strong></td>
                                            <td>{{$data->cof}}</td>
                                        </tr> 
                                        <tr>
                                            <td><strong>SIT</strong></td>
                                            <td>{{$data->sit}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>GSM</strong></td>
                                            <td>{{$data->gsm}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Special Feature</strong></td>
                                            <td>{{$data->special_feature}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Category Status</strong></td>
                                            <td>{{displayStatus($data->status)}}</td>
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
</section>
