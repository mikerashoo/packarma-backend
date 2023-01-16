<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Packaging Solution Details</h5>
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
                                            <td class="col-sm-5"><strong>Packaging Solution Name</strong></td>
                                            <td>{{$data->engine_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Structure Type</strong></td>
                                            <td>{{$data->structure_type}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Minimum Shelf Life</strong></td>
                                            <td>{{$data->min_shelf_life}} (Days)</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Maximum Shelf Life</strong></td>
                                            <td>{{$data->max_shelf_life}} (Days)</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Display Shelf Life</strong></td>
                                            <td>{{$data->display_shelf_life}} (Days)</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Minimum Weight</strong></td>
                                            <td>{{$data->min_weight.' '.$data->measurement_unit->unit_symbol}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Maximum Weight</strong></td>
                                            <td>{{$data->max_weight.' '.$data->measurement_unit->unit_symbol}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Minimum Order Quantity</strong></td>
                                            <td>{{$data->min_order_quantity.' '.$data->min_order_quantity_unit}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Product Name</strong></td>
                                            <td>{{$data->product->product_name}}</td>
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
                                            <td><strong>Storage Condition</strong></td>
                                            <td>{{$data->storage_condition->storage_condition_title}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Category Status</strong></td>
                                            <td>{{displayStatus($data->status)}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date Time</strong></td>
                                            <td>{{date('d-m-Y h:i A', strtotime($data->updated_at)) }}</td>
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
