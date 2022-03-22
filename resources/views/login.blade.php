<!DOCTYPE html>
<html lang="en"> 

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content=""> 
    <title></title>
    
    <!-- Core CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/bootstrap-4.3.1/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/bootstrap-select/dist/css/bootstrap-select.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/DataTables/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/DataTables/Responsive/css/responsive.bootstrap4.min.css')}}">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/helper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/style.css')}}">
    
    <!-- Jquery -->
    <script type="text/javascript" src="{{asset('admin_assets/plugins/jquery/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/js/popper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/bootstrap-4.3.1/js/bootstrap.min.js')}}"></script>
    
</head>
<body class="fix-header fix-sidebar">

    <!-- Preloader -->
    <!-- <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/> 
        </svg>
    </div> -->

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
                            <form method="post" action="{{route('auth')}}">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-12 mb-4">
                                        <label class="col-form-label">Email</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Email" autocomplete="off" required="" value="">
                                    </div>

                                    <div class="form-group col-md-12 ">
                                        <label class="col-form-label">Password</label>
                                        <input type="password" class="form-control" id="log_password" name="log_password" placeholder="Enter Password" autocomplete="off" required="" value="">  
                                    </div>

                                    <div class="col-md-12">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary" id="save_admin_login">Log In</button>
                                        </div>
                                    </div>                                   
                                                                       
                                    @php
                                    {{if(session('error')){
                                    @endphp
                                        <div class="col-md-12 mt-3">
                                            <div class="text-center">
                                                <div class="alert alert-danger" role="alert">{{session('error')}}</div>
                                            </div>
                                        </div>
                                    @php
                                    }}}                                    
                                    @endphp
                                    
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

    <!-- Jquery -->  
    <script type="text/javascript" src="{{asset('admin_assets/js/jquery.slimscroll.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/js/sidebarmenu.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/bootstrap-select/dist/js/bootstrap-select.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/DataTables/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/DataTables/Responsive/js/dataTables.responsive.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/bootbox-master/dist/bootbox.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/parsley/parsley.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/js/scripts.js')}}"></script>
</body>
</html>