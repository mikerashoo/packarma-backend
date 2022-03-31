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
                                    <h4 class="card-title text-center">General Info</h4>
                                </div>
                                <hr>
                            	<div class="card-body">
                            		<form method="post" id="profileForm" action="updateProfile">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Name</label>
                                                <input class="form-control" type="text" name="admin_name" value="{{$data->admin_name}}"><br/>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Email Id</label>
                                                <input class="form-control" type="text" name="email" value="{{$data->email}}"><br/>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Contact Number</label>
                                                <input class="form-control" type="text" name="phone" value="{{$data->phone}}"><br/>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="pull-left">
                                                    @if ($message = Session::get('success'))
                                                        <div class="successAlert alert text-success">
                                                            <strong>{{ $message }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="pull-right">
                                                    <button type="button" class="btn btn-success" onclick="submitForm('profileForm','post')">Update</button>
                                                    <!-- <button type="submit" class="btn btn-success btn-sm">Update Info</button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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