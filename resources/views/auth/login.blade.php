@extends('layouts.master')

@section('content')
 <!-- Main wrapper  --> 
 <div id="main-wrapper">
    <div class="container">  
        <div class="row justify-content-center m-t-100">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card">
                    <div class="card-header pt-4 pb-4 bg-primary border-0">
                        <div class="app-brand text-center">
                            <a href="javascript:void(0)"> 
                                <img src="images/general_settings/22340admin_logo_2.png" alt="homepage" class="dark-logo" style="height: 25px;">
                                <span class="brand-name text-white f-w-600">Codepoint</span>
                            </a>
                        </div>
                    </div><!-- /.card-header -->

                    <div class="card-body p-4">
                        <h5 class="f-w-400 text-dark mt-2 f-s-16">Welcome Back !</h5>
                        <h6 class="font-weight-light mb-4 f-s-13">Sign in to continue.</h6>
                        <div class="result"></div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-12 mb-4">
                                    <label class="col-form-label">Email</label>
                                    <input type="email" class="@error('email') is-invalid @enderror form-control" id="login_email" name="email" placeholder="Enter Email" autocomplete="off" required="" value="{{ old('email') }}">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12 ">
                                    <label class="col-form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="log_password" name="password" placeholder="Enter Password" autocomplete="off" required="" value=""> 
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror 
                                </div>

                                <div class="col-md-12">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-lg btn-primary" id="save_admin_login">{{ __('Login') }} </button>
                                    </div>
                                    @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                                </div> 

                                                                   
                                
                                
                            </div>
                        </form>
                    </div><!-- /.card-header -->
                </div><!-- /.card -->
                <p class="text-center mb-0 mt-3">©2019-2020 All rights reserved. Designed by
                    <a class="text-primary" href="#" target="_blank">Globintec</a>.
                </p>
            </div><!-- /.col-xl-5 col-lg-6 col-md-10 -->
        </div><!-- /.row --> 
    </div><!-- /.container-fluid --> 
</div><!-- /.main-wrapper -->

@endsection
