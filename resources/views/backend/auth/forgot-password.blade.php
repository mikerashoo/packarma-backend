@extends('backend.layouts.applogin')
@section('title', 'Reset Password Email')
@section('content')
    <div class="wrapper">
        <div class="main-panel">
            <div class="main-content">
                <div class="content-overlay"></div>
                <div class="content-wrapper">
                    <section id="forgot-password" class="auth-height">
                        <div class="row full-height-vh m-0 d-flex align-items-center justify-content-center">
                            <div class="col-md-7 col-12">
                                <div class="card overflow-hidden">
                                    <div class="card-content">
                                        <div class="card-body auth-img">
                                            <div class="row m-0">
                                                 <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center auth-img-bg p-3">
                                                <img src={{url('public/backend/img/Packarma_logo_1.svg')}} alt="" class="img-fluid" width="300" height="230">
                                                {{-- <h1 style="font-size: 70px;">&nbsp;&nbsp;PACKULT&nbsp;&nbsp;</h1> --}}
                                            </div>
                                                <div class="col-lg-6 col-md-12 px-4 py-3">
                                                    <form method="POST" action="{{ route('password.email') }}">
                                                        @csrf
                                                        <h4 class="mb-2 card-title">Recover Password</h4>
                                                        <p class="card-text mb-3">Please enter your email address and we'll send you instructions on how to reset your password.</p>
                                                        @php
                                                        $status = session('status');
                                                        @endphp
                                                        @if($status)
                                                            <div class='badge bg-light-success mb-1 mr-2'>
                                                                {{ $status }}
                                                            </div>
                                                        @endif
                                                        <input type="email" name="email" class="form-control mb-3" placeholder="Email" value="{{old('email')}}">
                                                        <div class="d-flex flex-sm-row flex-column justify-content-between">
                                                            <a href="{{url('/webadmin')}}" class="btn bg-light-primary mb-2 mb-sm-0">Back To Login</a>
                                                            <button class="btn btn-primary" type="submit">Recover</button>
                                                            
                                                        </div><br>
                                                        @if($errors->any())
                                                            <div class="text-center">
                                                                <span style="color:red">{{$errors->first()}}</span><br/>
                                                            </div>
                                                        @endif
                                                    </form>
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
        </div>
    </div>
@endsection
  