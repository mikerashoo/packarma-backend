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
                                            <td><strong>Measurement Unit Name</strong></td>
                                            <td>{{$data->unit_name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Measurement Unit Symbol</strong></td>
                                            <td>{{$data->unit_symbol}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Measurement Unit Form</strong></td>
                                            <td>{{measurementUnitForm($data->unit_form)}}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td><strong>Date Time</strong></td>
                                            <td>{{ date('d-m-Y H:i A', strtotime($data->updated_at)) }}</td>
                                        </tr> --}}
                                        <tr>
                                            <td><strong>Unit Status</strong></td>
                                            <td>{{displayStatus($data->status)}}</td>
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
