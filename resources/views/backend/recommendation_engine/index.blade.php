@extends('backend.layouts.app')
@section('content')
<div class="main-content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <section class="users-list-wrapper">
        	<div class="users-list-table">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12 col-sm-7">
                                            <h5 class="pt-2">Manage Packaging Solution List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            @if($data['packaging_solution_add'])
                                                <a href="packaging_solution_add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Packaging Solution</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            	<div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Packaging Solution Name</label>
                                            <input class="form-control mb-3" type="text" id="search_recommendation_engine" name="search_recommendation_engine">
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Structure Type</label>
                                            <select class="form-control mb-3 select2" id="search_structure_type" name="search_structure_type" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['solutionStructureType'] as $key => $structureType)
                                                    <option value="{{$key}}">{{$structureType}}</option>                                                
                                                @endforeach
                                            </select><br/><br/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Storage Condition</label>
                                            <select class="form-control mb-3 select2" id="search_storage_condition" name="search_storage_condition" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['storage_condition'] as $condition)
                                                    <option value="{{$condition->id}}">{{$condition->storage_condition_title}}</option>                                                
                                                @endforeach
                                            </select><br/><br/>
                                        </div>
                                        {{-- <div class="col-sm-4">
                                            <label>Measurement Unit</label>
                                            <select class="form-control mb-3 select2" id="search_measurement_unit" name="search_measurement_unit" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['measurement_unit'] as $unit)
                                                    <option value="{{$unit->id}}">{{$unit->unit_symbol}}</option>                                                
                                                @endforeach
                                            </select><br/><br/>
                                        </div> --}}
                                        <div class="col-sm-4">
                                            <label>Product Name</label>
                                            <select class="form-control mb-3 select2" id="search_product_name" name="search_product_name" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['product'] as $product)
                                                    <option value="{{$product->id}}">{{$product->product_name}}</option>                                                
                                                @endforeach
                                            </select><br/><br/>
                                        </div>
                                        {{-- <div class="col-sm-4">
                                            <label>Category Name</label>
                                            <select class="form-control mb-3 select2" id="search_category_name" name="search_category_name" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['category'] as $cat)
                                                    <option value="{{$cat->id}}">{{$cat->category_name}}</option>                                                
                                                @endforeach
                                            </select><br/><br/>
                                        </div> --}}
                                        <div class="col-sm-4">
                                            <label>Product Form Name</label>
                                            <select class="form-control mb-3 select2" id="search_product_form" name="search_product_form" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['product_form'] as $form)
                                                    <option value="{{$form->id}}">{{$form->product_form_name}}</option>                                                
                                                @endforeach
                                            </select><br/><br/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Packaging Treatment Name</label>
                                            <select class="form-control mb-3 select2" id="search_packaging_treatment" name="search_packaging_treatment" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['packaging_treatment'] as $treatment)
                                                    <option value="{{$treatment->id}}">{{$treatment->packaging_treatment_name}}</option>                                                
                                                @endforeach
                                            </select><br/><br/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Packing Type Name</label>
                                            <select class="form-control mb-3 select2" id="search_packing_type" name="search_packing_type" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['packing_type'] as $type)
                                                    <option value="{{$type->id}}">{{$type->packing_name}}</option>                                                
                                                @endforeach
                                            </select><br/><br/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Packaging Machine Name</label>
                                            <select class="form-control mb-3 select2" id="search_packaging_machine" name="search_packaging_machine" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['packaging_machine'] as $machine)
                                                    <option value="{{$machine->id}}">{{$machine->packaging_machine_name}}</option>                                                
                                                @endforeach
                                            </select><br/><br/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Packaging Material Name</label>
                                            <select class="form-control mb-3 select2" id="search_packaging_material" name="search_packaging_material" style="width: 100% !important;">
                                                <option value="">Select</option>
                                                @foreach($data['packaging_material'] as $material)
                                                    <option value="{{$material->id}}">{{$material->packaging_material_name}}</option>                                                
                                                @endforeach
                                            </select><br/><br/>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                            		<div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="packaging_solution_data">
				                            <thead>
				                                <tr>
				                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="engine_name" data-orderable="false" data-searchable="false">Packaging Solution Name</th>
                                                    <th id="structure_type" data-orderable="false" data-searchable="false">Structure Type</th>
                                                    <th id="product_name" data-orderable="false" data-searchable="false">Product Name</th>
                                                    @if($data['packaging_solution_view'] || $data['packaging_solution_edit'] || $data['packaging_solution_status'])
                                                        <th id="action" data-orderable="false" data-searchable="false" width="130px">Action</th>
                                                    @endif
				                                </tr>
				                            </thead>
				                        </table>
                                    </div>
                            	</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection