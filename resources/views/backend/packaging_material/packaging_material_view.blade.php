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
                                            <td><strong>Packaging Material Name</strong></td>
                                            <td>{{$data->packaging_material_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Material Description</strong></td>
                                            <td>{{$data->material_description}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Shelf Life</strong></td>
                                            <td>{{$data->shelf_life}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Price</strong></td>
                                            <td>{{$data->approx_price}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Packaging Material Status</strong></td>
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
