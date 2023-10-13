<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Permission Role : {{$data['roleData']['role_name']}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <!-- <hr class="mb-0"> -->
                    	<div class="card-body">
                            <div class="card-content">
                    	        <div class="card-body">

                   
                                    <div class="col-12 users-module">
                                        <div class="table-responsive" style="height:450px;">
                                            <table class="table mb-0 mt-3" id="roleTable">
                                                <thead style="position: sticky; position: -webkit-sticky; top: 0;  background-color: #d6d6d6; color: #000000;  z-index: 1;">
                                                    <tr>
                                                        <th>Permissions</th>
                                                        @foreach($data['permission_types'] as $type)
                                                        <th>{{$type}}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data['permissions'] as $details)
                                                    <tr>
                                                        @foreach($details as $k => $item)
                                                            @if($k  == 'permission')
                                                                <td><strong>{{$item['label']}}</strong></td>
                                                            @else
                                                                @if(empty($item['id']))
                                                                    <td></td>
                                                                @else    
                                                                    @if(in_array($item['id'], $data['role_permissions']))
                                                                        <td><input type="checkbox" data-url="publishPermission?id={{$data['roleData']['id']}}" id="switchery{{$item['id']}}" data-id="{{$item['id']}}" class="js-switch switchery" checked></td>
                                                                    @else
                                                                        <td><input type="checkbox" data-url="publishPermission?id={{$data['roleData']['id']}}" id="switchery{{$item['id']}}" data-id="{{$item['id']}}" class="js-switch switchery"></td>
                                                                    @endif
                                                                @endif   
                                                            @endif	
                                                        @endforeach		
                                                    </tr>
                                                    @endforeach
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
    </div>
</section>
<script>
    
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function(html) {
        var switchery = new Switchery(html, { color: '#11c15b', jackColor: '#fff', size: 'small', secondaryColor: '#ff5251'});
    });
</script>