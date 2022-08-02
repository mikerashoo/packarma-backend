@extends('backend.layouts.applogin')
@section('title', 'Login')
@section('content')
<div class="wrapper">
    <div class="main-panel">
        <div class="main-content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <section id="login" class="auth-height">
                    <div class="row full-height-vh m-0">
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <div class="card overflow-hidden">
                                <div class="card-content">
                                    <div class="card-body auth-img">
                                        <div class="row m-0">
                                            <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center auth-img-bg p-3">
                                                <img src="public/backend/img/Packarma_logo_1.svg" alt="" class="img-fluid" width="300" height="230">
                                                {{-- <h1 style="font-size: 70px;">&nbsp;&nbsp;PACKULT&nbsp;&nbsp;</h1> --}}
                                            </div>
                                            <form class="col-lg-6 col-12 px-4 py-3" action="webadmin/login" method="POST">
                                                @csrf
                                                <h4 class="mb-2 card-title">Admin Team Login</h4>
                                                <br/>
                                                        @php
                                                        $status = session('status');
                                                        @endphp
                                                        @if($status)
                                                            <div class='badge bg-light-success mb-1 mr-2'>
                                                                {{ $status }}
                                                            </div>
                                                        @endif
                                                <div class="mb-3">
                                                    <input type="email" class="form-control" placeholder="Email" required="" name="email">
                                                </div>
                                                <div class="mb-3">
                                                    <input type="password" class="form-control mb-2" placeholder="Password" required="" name="password">

                                                    <div class="d-sm-flex justify-content-between mb-3 font-small-2">
                                                            @if(Route::has('password.request'))
                                                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                                                {{ __('Forgot your password?') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="pull-right">
                                                            <button class="btn btn-primary">Login</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="pull-right">
                                                            @error('msg')
                                                                <div class="text-danger">{{$message}}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <hr style="margin-top: 0;">
                                        <div class="col-sm-12">
                                            <p class="text-center text-dark">Powered by <a class="text-dark" href="https://www.mypcot.com/" target="_blank">Mypcot Infotech</a></p>
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
