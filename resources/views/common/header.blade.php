<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content=""> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <!-- Core CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/bootstrap-4.3.1/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/bootstrap-select/dist/css/bootstrap-select.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/DataTables/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/DataTables/Responsive/css/responsive.bootstrap4.min.css')}}">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/helper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/plugins/bootstrap_datetime_picker/bootstrap-datetimepicker.min.css')}}">

    <!-- Jquery -->
    <script type="text/javascript" src="{{asset('admin_assets/plugins/jquery/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/js/popper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin_assets/plugins/bootstrap-4.3.1/js/bootstrap.min.js')}}"></script>
