@extends('backend.layouts.applogin')
@section('title', 'URL Expireed')
@section('content')
<div class="wrapper">
        <div class="main-panel">
            <!-- BEGIN : Main Content-->
            <div class="main-content">
                <div class="content-overlay"></div>
                <div class="content-wrapper">
                    <!--Error page starts-->
                    <section id="error" class="auth-height">
                        <div class="container-fluid">
                            <div class="row full-height-vh">
                                <div class="col-12 d-flex align-items-center justify-content-center">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <img src={{url('public/backend/img/Packarma_logo_1.svg')}} alt="" class="img-fluid error-img mt-2" height="300" width="230">
                                            <h1 class="mt-4">404 - Page Not Found!</h1>
                                            <div class="w-75 error-text mx-auto mt-4">
                                                <p>The page you are looking for might have been removed, had it's name changed, or is temporarily unavailable.</p>
                                            </div>
                                            <a href="{{url('/')}}" class="btn btn-warning my-2">Back To Login</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--Error page ends-->

                </div>
            </div>
            <!-- END : End Main Content-->
        </div>
@endsection
