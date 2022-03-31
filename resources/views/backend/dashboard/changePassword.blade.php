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
                                    <h4 class="card-title text-center">Update Password</h4>
                                </div>
                                <hr>
                            	<div class="card-body">
                            		<form method="post" id="changePwdForm" action="resetPassword">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Old Password</label>
                                                <input class="form-control" type="text" name="old_password">
                                            </div>
                                            <div class="col-md-4">
                                                <label>New Password</label>
                                                <input class="form-control" type="password" name="new_password">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Confirm Password</label>
                                                <input class="form-control" type="password" name="confirm_password">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="pull-right">
                                                <button type="button" class="btn btn-success" onclick="submitForm('changePwdForm','post')">Reset Password</button>
                                                    <!-- <button type="submit" class="btn btn-success btn-sm">Reset Password</button> -->
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